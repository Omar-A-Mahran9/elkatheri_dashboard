<?php

namespace App\Http\Controllers\API;



use App\Models\City;
use App\Models\Brand;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\ZohoToken;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
class AppointmentController extends Controller
{
    use NotificationTrait;

    public $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request)
    {

        $this->authorize('view_appointments');

        if ($request->ajax())
        {
            $data = getModelData( model : new Appointment() );

            return response()->json($data);
        }

        return view('dashboard.appointments.index');
    }

     public function formData()
    {
        $brandsWithModels = Brand::whereHas('cars')->get()->map(function($brand){
            return [
                "id" => $brand->id,
                "name" => $brand->name,
                "models" => $brand->parentModels->map(function($model){
                    return [
                        'id' => $model->id,
                        'name' => $model->name,
                    ];
                }),
            ];
        });

        $citiesWithBranches = City::has('branches')->with('branches')->whereHas('branches',function($query){
            $query->where('type','maintenance_center')->orWhere('type', '3s_center')->whereHas('schedule');
        })->get()->map(function($city){
            return [
                "id" => $city->id,
                "name" => $city->name,
               "branches" => $city->branches->filter(function($branch) {
                return $branch->type === 'maintenance_center' || $branch->type === '3s_center';
            })->map(function($branch){
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name,
                        'available_days' => $branch->schedule->where('is_available',1)->pluck('day_of_week')

                    ];
                }),
            ];
        });

        return response()->json([
            "days_of" => Schedule::whereIsAvailable(false)->get()->pluck('day_of_week')->toArray(),
            "start_date" => now()->format('Y-m-d'),
            "end_date" => now()->addMonths(2)->format('Y-m-d'),
            "brands" => $brandsWithModels,
            "cities" => $citiesWithBranches,
        ]);
    }
    public function store(StoreAppointmentRequest $request)
    {
         $appointment = $this->appointmentService->store($request);


       // Retrieve cookies
        $utmSource = $request->cookie('utm_source');
        $utmMedium = $request->cookie('utm_medium');
        $utmCampaign = $request->cookie('utm_campaign');

        // Query the campaigns table using multiple UTM parameters
        $campaign = Campaign::where('campaign_name', $utmCampaign)
                            ->where('campaign_source', $utmSource)
                            ->where('campaign_medium', $utmMedium)
                            ->first(); // Retrieve the first matching campaign

        // Check if at least one of the UTM cookies has a value and a valid campaign exists
        if (($utmSource || $utmMedium || $utmCampaign    ) && $campaign) {
            // Proceed to create the OrderTracking only if any cookie is not null and campaign is found
            OrderTracking::create([
                'appointment_id' => $appointment->id,
                'campaign_id' => $campaign->id, // Use the campaign ID
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
              ]);
        }
        $this->newAppointmentNotification($appointment);
       $formattedPhone = formatSaudiPhoneNumber($appointment->phone);
         try {
            Mail::send('mails.maintenance',[ 'appointment' =>  $appointment, 'branch_mail' => 1 ],function($message) use($appointment){
                $message->to($appointment->branch->email)->subject(__('New maintenance appointment'));
            });
            Mail::send('mails.maintenance',[ 'appointment' =>  $appointment ],function($message) use($appointment){
                $message->to($appointment->email)->subject(__('Maintenance appointment details'));
            });
                  Mail::send('mails.maintenance',[ 'appointment' =>  $appointment ],function($message) use($appointment){
                $message->to(settings()->get('email'))->subject(__('New maintenance appointment'));
            }) ;


            // Send SMS reminder
             $smsResult = SendMaintenanceReminder(
            $formattedPhone,
            $appointment->id,
             $dateOnly = $appointment->date ? date('Y-m-d', strtotime($appointment->date)) : null,

            $timeOnly = $appointment->time ? date('g:i a', strtotime($appointment->time)) : null,


            $appointment->branch->name
        );



        if($appointment == null)
        {
            throw ValidationException::withMessages([
                'time' => __("This time is reserved")
            ]);

        }

        $errorDetails = null;

        if (!$smsResult['success']) {
            $errorDetails = [
                'error' => 'Failed to send SMS reminder',
                'details' => $smsResult['error'],
                'params' => $smsResult['params']
            ];
        }
        $this->sendOrderToZoho($appointment);

        // Return the appointment with error details if any
        return response()->json([
            'data' => new AppointmentResource($appointment),
            'errors' => $errorDetails
        ]);






        } catch (\Throwable $th) {
            return ($th->getMessage()) ;
        }


    }


    public function sendOrderToZoho(Appointment $order)
    {
        // Retrieve the Zoho access token from the DB
        $token = ZohoToken::first();

        // Check if the access token is expired and refresh it if necessary
        if ($this->isTokenExpired($token)) {
            $this->refreshZohoAccessToken($token);
        }

        // Split the full name into first and last name
        $nameParts = explode(' ', $order->name, 2); // Split the name by the first space

        // Default values in case of no space in the name
        $firstName = $nameParts[0] ?? 'Unknown';
        $lastName = $nameParts[1] ?? 'Unknown';

        $carName = $order->brand->name ?? 'غير محدد';
        $carYear = $order->model_year ?? 'غير معروف';

        // جلب تتبع الحملة المرتبط بالطلب
        $orderTracking = $order->tracking ?? null;

        $utmSource = $orderTracking->utm_source ?? 'غير محدد';
        $utmMedium = $orderTracking->utm_medium ?? 'غير محدد';
        $utmCampaign = $orderTracking->utm_campaign ?? 'غير محدد';
 
        $description = "تفاصيل الطلب:\n"
                     . "رقم الطلب: {$order->id}\n"
                     . "اسم السيارة: {$carName}\n"
                     . "موديل السيارة: {$carYear}\n"
                     . "المدينة: " . ($order->city_name ?? 'غير محددة') . "\n"
                     . "الاسم: " . ($order->name ?? 'غير معروف') . "\n"
                     . "رقم الجوال: " . ($order->phone ?? 'غير متوفر') . "\n"
                     . "رابط الطلب: https://admin.alkathirimotors.com.sa/dashboard/appointments/{$order->id}\n"
                     . "معلومات الحملة:\n"
                     . "- المصدر: {$utmSource}\n"
                     . "- الوسيط : {$utmMedium}\n"
                     . "- اسم الحملة : {$utmCampaign}\n";


        // Retrieve UTM parameters from cookies
        $tracking = $order->tracking; // assuming `hasOne('App\Models\OrderTracking')`


        // Prepare the Zoho lead data
        $orderData = [
            'data' => [
                [
                    "First_Name" => $firstName ?? " ",  // Customer's first name
                    "Last_Name" => $lastName ?? " ",    // Customer's last name
                    "Email" => $order->email ?? $order->organization_email ?? 'noemail@example.com', // Customer's email
                    "Phone" => $order->phone ?? " ",    // Customer's phone number
                    "Mobile" => $order->phone ?? " ",   // Customer's mobile number
                    "Company" => $order->organization_name ?? 'No Organization',  // Organization (if any)
                    "Lead_Source" => 'website',  // Source of the lead
                    "Lead_Status" => 'New',      // Status of the lead (New by default)
                    "Industry" => 'agency',     // Industry type (hardcoded as 'agency' for now)
                    "Car_Model" => $order->car->model->name?? 'N/A',    // Industry type (hardcoded as 'agency' for now)
                    "Model_Year" => $order->car->year?? 'N/A',    // Industry type (hardcoded as 'agency' for now)

                    "Website" => 'https://alkathirimotors.com.sa/', // Website URL
                    "City" => $order->city_name ?? 'Unknown City',  // City
                    "Description" => $description ?? 'No description',  // Description of the lead
                    "Lead_Type" => 'Warm',  // Lead type (Hot, Warm, Cold)
                    "Created_Time" => $order->created_at->toIso8601String() ?? now()->toIso8601String(), // Lead creation time
                    "Modified_Time" => $order->updated_at->toIso8601String() ?? now()->toIso8601String(), // Lead modified time
                    // Include UTM parameters in the Zoho request payload
                      "UTM_Source" => $tracking->utm_source ?? 'N/A',
                    "UTM_Medium" => $tracking->utm_medium ?? 'N/A',
                    "UTM_Campaign" => $tracking->utm_campaign ?? 'N/A',
                 ]
            ]
        ];

        // Send the data to Zoho CRM using the Zoho API
        $response = Http::withToken($token->access_token)
                        ->acceptJson()  // Ensure JSON response is accepted
                        ->post('https://www.zohoapis.com/crm/v2/Leads', $orderData);


               $dealData = [
                            'data' => [
                                [
                                    'Deal_Name' =>  $order->name,  // اسم العرض
                                    'Deal_Owner' => $order->name,  // اسم العرض
                                    "Phone" => $order->phone ?? " ",    // Customer's phone number
                                    "Email" => $order->email ?? $order->organization_email ?? 'noemail@example.com', // Customer's email
                                    'Amount' => $order->price ?? 0,  // السعر (إن وجد)
                                    'Closing_Date' => now()->addDays(14)->toDateString(),  // تاريخ الإغلاق المتوقع
                                    'Lead_Source' =>   'website',
                                    'Description' => $description,
                                    "Purchase_style"=>$order->payment_type,
                                    "UTM_Source" => $tracking->utm_source ?? 'N/A',
                                    "UTM_Medium" => $tracking->utm_medium ?? 'N/A',
                                    "UTM_Campaign" => $tracking->utm_campaign ?? 'N/A',
                                 ]
                                ]

                        ];

        $dealResponse = Http::withToken($token->access_token)
                                            ->acceptJson()
                                            ->post('https://www.zohoapis.com/crm/v2/Deals', $dealData);


        // Check if the response is successful
        if ($response->successful()) {
            return response()->json([
                'message' => 'Order sent to Zoho CRM successfully.',
                'data' => $response->json()  // Return the Zoho response data for confirmation
            ]);
        } else {
            // Handle failure case: Log the error for debugging
            return response()->json([
                'message' => 'Failed to send order to Zoho CRM.',
                'error' => $response->json(),  // Return the error response from Zoho for debugging
            ], 400);
        }
    }

    private function isTokenExpired($token)
    {
        // Get the current time in Unix timestamp
        $currentTime = now()->timestamp; // Current timestamp

        // Convert the 'expires_at' (which is in datetime format) to a Unix timestamp
        $expiresAt = \Carbon\Carbon::parse($token->expires_at)->timestamp;

        // Check if the token has expired
        return $currentTime > $expiresAt;
    }

    public function refreshZohoAccessToken()
    {
        $token = ZohoToken::first(); // استرجاع التوكن من قاعدة البيانات
         $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'refresh_token' => $token->refresh_token,
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
        ]);

        $data = $response->json();

        // تحديث التوكن في قاعدة البيانات
        ZohoToken::updateOrCreate(
            ['id' => 1],  // أو استخدم أي معرّف ترغب به
            [
                'access_token' => $data['access_token'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]
        );

        return response()->json($data);  // إرجاع التوكنات الجديدة
    }



}
