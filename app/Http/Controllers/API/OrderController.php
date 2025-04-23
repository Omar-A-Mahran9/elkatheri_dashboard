<?php

namespace App\Http\Controllers\API;

use App\Models\Car;
use App\Models\Bank;
use App\Models\City;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Service;
use App\Traits\NotificationTrait;
use App\Http\Resources\CarResource;
use App\Models\FundingOrganization;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankResource;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\storeUnavailableCarRequest;
use App\Http\Requests\StoreCorporatesOrderRequest;
use App\Http\Requests\StoreIndividualOrderRequest;
use App\Http\Requests\storeofferorderRequest;
use App\Http\Resources\FundingOrganizationResource;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Offer;
use App\Models\OrderTracking;
use App\Models\ZohoToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use NotificationTrait;

    public function storeIndividualOrder(StoreIndividualOrderRequest $request)
    {
        $data = $request->validated();
        $data['type'] = 'individual';
        $car = Car::find($data['car_id']);
        $data['price'] = $car->selling_price_after_vat;
        if($request->funding_organization_type == 'same_bank')
            $data['funding_bank_id'] = $data['bank_id'];

        if ($request->file('id_and_driving_license'))
            $data['id_and_driving_license'] = uploadImage( $request->file('id_and_driving_license') , "Orders");

        if ($request->file('salary_identification'))
            $data['salary_identification'] = uploadImage( $request->file('salary_identification') , "Orders");

        if ($request->file('insurance_print'))
            $data['insurance_print'] = uploadImage( $request->file('insurance_print') , "Orders");

        if ($request->file('account_statement'))
            $data['account_statement'] = uploadImage( $request->file('account_statement') , "Orders");
        $data['terms_and_privacy'] = $data['terms_and_privacy'] ? 1 : 0;
        $data['phone'] = convertArabicNumbers($data['phone']);

        $order = Order::create($data);

        // Retrieve cookies
        $utmSource = $request->cookie('utm_source');
        $utmMedium = $request->cookie('utm_medium');
        $utmCampaign = $request->cookie('utm_campaign');

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
                'order_id' => $order->id,
                'campaign_id' => $campaign->id, // Use the campaign ID
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
              ]);
        }

        $order->services()->attach($request['services'] ?? []);
        $this->sendOrderToZoho($order);

        $this->sendMail($order);
        return response()->json(["individual order created successfully"]);
    }

    public function storeCorporatesOrder(StoreCorporatesOrderRequest $request)
    {
        $data = $request->validated();
        $data['type'] = 'organization';

        if ($request->file('bank_number_image'))
            $data['bank_number_image'] = uploadImage( $request->file('bank_number_image') , "Orders");

        if ($request->file('id_and_driving_license'))
            $data['id_and_driving_license'] = uploadImage( $request->file('id_and_driving_license') , "Orders");

        if ($request->file('salary_identification'))
            $data['salary_identification'] = uploadImage( $request->file('salary_identification') , "Orders");

        if ($request->file('insurance_print'))
            $data['insurance_print'] = uploadImage( $request->file('insurance_print') , "Orders");

        if ($request->file('account_statement'))
            $data['account_statement'] = uploadImage( $request->file('account_statement') , "Orders");
        $data['terms_and_privacy'] = $data['terms_and_privacy'] ? 1 : 0;
        $data['phone'] = convertArabicNumbers($data['phone']);

        $order = Order::create($data);

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
                  'order_id' => $order->id,
                  'campaign_id' => $campaign->id, // Use the campaign ID
                  'utm_source' => $utmSource,
                  'utm_medium' => $utmMedium,
                  'utm_campaign' => $utmCampaign,
                  ]);
          }
        $order->services()->attach($request['services'] ?? []);
        $this->sendOrderToZoho($order);

        $this->sendMail($order);
        return response()->json(["corporate order created successfully"]);
    }

    public function purchaseInfo()
    {
        return response([
            'cars' => CarResource::collection(Car::get()),
            'banks' => BankResource::collection(Bank::get()),
            'cities' => CityResource::collection(City::get()),
            'services' => ServiceResource::collection(Service::whereActive(true)->get()),
            'funding_organizations' => FundingOrganizationResource::collection(FundingOrganization::get()),
        ]);
    }

    public function unavailableCar(storeUnavailableCarRequest $request){

        $data = $request->validated();
        $data['type'] = 'unavailable_car';
        $data['phone'] = convertArabicNumbers($data['phone']);
         $data['terms_and_privacy'] = $data['terms_and_privacy'] ? 1 : 0;



        $order = Order::create($data);

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
                'order_id' => $order->id,
                'campaign_id' => $campaign->id, // Use the campaign ID
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
              ]);
        }

        $this->newUnavailableCarNotification($order);
        $this->sendMail($order);
        $this->sendOrderToZoho($order);

        return response()->json(["order created successfully"]);
    }

    public function OfferOrder(storeofferorderRequest $request, $offer)
    {
        $data = $request->validated();

        // Check if the offer_id exists in the offers table
        if (!Offer::find($offer)) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        // Check if city_id exists
        if (!City::find($data['city_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'City not found'
            ], 404);
        }

        // Check if car_id exists
        if (!Car::find($data['car_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found'
            ], 404);
        }

        // Add the offer_id from the route to the order data
        $data['offer_id'] = $offer;

        // Prepare the rest of the order data
        $data['type'] = 'offer_order';
        $data['phone'] = convertArabicNumbers($data['phone']);
        $data['terms_and_privacy'] = $data['terms_and_privacy'] ? 1 : 0;

        // Create the order
        $order = Order::create($data);
     // Retrieve cookies
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
                'order_id' => $order->id,
                'campaign_id' => $campaign->id, // Use the campaign ID
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
              ]);
        }
        $this->sendOrderToZoho($order);

        // Optionally you can trigger notifications or emails here
        // $this->newOfferOrderNotification($order);
        // $this->sendMail($order);
       $this->sendOrderToZoho($order);


        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'order_id' => $order->id
        ]);
    }

    public function filterData()
    {
        $maxPrice = Car::max('price');
        $minPrice = Car::min('price');

        $filterData = Brand::has('cars')->with(['parentModels'])->get()->map(function($brand){
            return [
                "id" => $brand->id,
                "image" => $brand->image_path,
                "name" => $brand->name,
                "parent_models" => $brand->parentModels->map(function($parentModel){
                    return [
                        'id' => $parentModel->id,
                        'name' => $parentModel->name,
                    ];
                })
            ];
        });

        return response()->json([
            'maxPrice' => $maxPrice,
            'minPrice' => $minPrice,
            'brands' => $filterData,
        ]);
    }

           public function sendMail($order)
        {
            try {
                $employees = Employee::get(); // Fetch employees outside the Mail::send call

                // Mail::send('mails.mail', ['order' => $order], function ($message) use ($employees) {
                //     // foreach ($employees as $emp) {
                //     //     $message->to($emp->email)->subject(__('New Car Order'));
                //     // }
                //     $message->to($order->organization_email)->subject(__('New Car Order'));

                // });

                   Mail::send('mails.mailadmin', ['order' => $order], function ($message) use ($employees) {
                    // foreach ($employees as $emp) {
                    //     $message->to($emp->email)->subject(__('New Car Order'));
                    // }
                    $message->to(settings()->get('email'))->subject(__('New Car Order')) ;

                });

            } catch (\Throwable $th) {
                dd($th->getMessage());
            }
        }
        public function sendOrderToZoho(Order $order)
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

            $carName = $order->car->name ?? 'غير محدد';
            $carYear = $order->car->year ?? 'غير معروف';

            // جلب تتبع الحملة المرتبط بالطلب
            $orderTracking = $order->tracking ?? null;

            $utmSource = $orderTracking->utm_source ?? 'غير محدد';
            $utmMedium = $orderTracking->utm_medium ?? 'غير محدد';
            $utmCampaign = $orderTracking->utm_campaign ?? 'غير محدد';
            $utmYear = $orderTracking->utm_year ?? 'غير معروف';

            $description = "تفاصيل الطلب:\n"
                         . "رقم الطلب: {$order->id}\n"
                         . "اسم السيارة: {$carName}\n"
                         . "موديل السيارة: {$carYear}\n"
                         . "المدينة: " . ($order->city_name ?? 'غير محددة') . "\n"
                         . "الاسم: " . ($order->name ?? 'غير معروف') . "\n"
                         . "رقم الجوال: " . ($order->phone ?? 'غير متوفر') . "\n"
                         . "رابط الطلب: https://admin.alkathirimotors.com.sa/dashboard/orders/{$order->id}\n"
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
                                        'Deal_Name' => "طلب رقم {$order->id}",  // اسم العرض
                                        'Deal_Owner' => $order->name,  // اسم العرض
                                        "Phone" => $order->phone ?? " ",    // Customer's phone number
                                        "Email" => $order->email ?? $order->organization_email ?? 'noemail@example.com', // Customer's email
                                        'Amount' => $order->price ?? 0,  // السعر (إن وجد)
                                        'Closing_Date' => now()->addDays(14)->toDateString(),  // تاريخ الإغلاق المتوقع
                                        'Lead_Source' =>    $tracking->utm_source ?? 'website',
                                        'Description' => $description,
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


        /**
         * Check if the Zoho access token is expired.
         *
         * @param  ZohoToken  $token
         * @return bool
         */
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
