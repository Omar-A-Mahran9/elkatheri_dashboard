<?php

namespace App\Http\Controllers\API;

use App\Models\ContactUs;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactUsResource;
use App\Http\Requests\storeContactUsRequest;
use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;
use App\Models\OrderTracking;
use App\Models\ZohoToken;
use Illuminate\Support\Facades\Http;

class ContactUsController extends Controller
{
    public function store(storeContactUsRequest $request)
    {
      // Format the Saudi phone number
      $formattedPhone = formatSaudiPhoneNumber($request->phone);

      // Get the validated data from the request
      $validatedData = $request->validated();

      // Replace the 'phone' field with the formatted phone number
      $validatedData['phone'] = $formattedPhone;

      // Create a new ContactUs record using the modified validated data
      $contactUsRequest = ContactUs::create($validatedData);
      $utmSource = $request->utm_source;
      $utmMedium = $request->utm_medium;
      $utmCampaign = $request->utm_campaign;

      // Query the campaigns table using multiple UTM parameters
      $campaign = Campaign::where('campaign_name', $utmCampaign)
                          ->where('campaign_source', $utmSource)
                          ->where('campaign_medium', $utmMedium)
                          ->first(); // Retrieve the first matching campaign

      // Check if at least one of the UTM cookies has a value and a valid campaign exists
      if (($utmSource || $utmMedium || $utmCampaign    ) && $campaign) {
        $track =OrderTracking::create([
              'contact_id' => $contactUsRequest->id,
              'campaign_id' => $campaign->id, // Use the campaign ID
              'utm_source' => $utmSource,
              'utm_medium' => $utmMedium,
              'utm_campaign' => $utmCampaign,
            ]);

      }
      $this->sendOrderToZoho($contactUsRequest);


       try {
        // Assuming you want to send to multiple recipients in a loop
        $employees = Employee::get(); // Fetch employees or any other recipient data

        Mail::send('mails.contact-us', [
            'reply' => $contactUsRequest->reply,
            'contactUs' => $contactUsRequest,
            'web' => true
        ], function($message) use ($contactUsRequest, $employees) {
            // foreach ($employees as $emp) {
            //     $message->to($emp->email)->subject(__('Contact Us Reply'));
            // }
             $message->to(settings()->get('email'))->subject(__('New contact'));

        });


     } catch (\Throwable $th) {
            dd($th->getMessage()) ;
        }

        return new ContactUsResource($contactUsRequest);
    }


    public function sendOrderToZoho(ContactUs $order)
    {
        // Retrieve the Zoho access token from the DB
        $token = ZohoToken::first();

        // Check if the access token is expired and refresh it if necessary
        if ($this->isTokenExpired($token)) {
            $this->refreshZohoAccessToken($token);
        }


        // جلب تتبع الحملة المرتبط بالطلب
        $orderTracking = $order->tracking ?? null;

        $utmSource = $orderTracking->utm_source ?? 'غير محدد';
        $utmMedium = $orderTracking->utm_medium ?? 'غير محدد';
        $utmCampaign = $orderTracking->utm_campaign ?? 'غير محدد';
        $description = "تفاصيل الطلب:\n"
                     . "رقم الطلب: {$order->id}\n"
                     . "الاسم بالكامل : {$order->name}\n"
                     . "الايميل: " . ($order->email ?? 'غير محددة') . "\n"
                     . "رقم الجوال: " . ($order->phone ?? 'غير متوفر') . "\n"
                     . "رابط الطلب:https://admin.alkathirimotors.com.sa/dashboard/contact-us/{$order->id}/edit\n"
                     . "معلومات الحملة:\n"
                     . "- المصدر: {$utmSource}\n"
                     . "- الوسيط : {$utmMedium}\n"
                     . "- اسم الحملة : {$utmCampaign}\n";


        // Retrieve UTM parameters from cookies
        $tracking = $order->tracking; // assuming `hasOne('App\Models\OrderTracking')`

        // Prepare the Zoho lead data
        $orderData = [
            $utm = [
                "UTM_Source" => $tracking->utm_source ?? 'N/A',
                "UTM_Medium" => $tracking->utm_medium ?? 'N/A',
                "UTM_Campaign" => $tracking->utm_campaign ?? 'N/A',
            ];

            $data = [
                "First_Name" => $order->name ?? 'Unknown',
                "Email" => $order->email ?? 'noemail@example.com',
                "Phone" => $order->phone ?? 'Unknown',
                "Lead_Source" => 'website',
                "Lead_Status" => 'New',
                "Industry" => 'agency',
                "Website" => 'https://alkathirimotors.com.sa/',
                "Description" => $description ?? 'No description',
                "Lead_Type" => 'Warm',
                "Created_Time" => optional($order->created_at)->toIso8601String() ?? now()->toIso8601String(),
                "Modified_Time" => optional($order->updated_at)->toIso8601String() ?? now()->toIso8601String(),
            ] + $utm;

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
