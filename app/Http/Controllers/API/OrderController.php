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
use App\Models\Employee;
use App\Models\Offer;
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

            $description = "طلب سيارة رقم: " . $order->id . " واسمها: " . ($order->car->name ?? '') . " وموديل: " . ($order->car->year ?? '');

            $orderData = [
                'data' => [
                    [
                        "First_Name" => $firstName??" ",  // Customer's first name
                        "Last_Name" => $lastName??" ",    // Customer's last name
                        "Email" => $order->email ?? $order->organization_email ?? 'noemail@example.com', // Customer's email
                        "Phone" => $order->phone??" ",    // Customer's phone number
                        "Mobile" => $order->phone??" ",   // Customer's mobile number
                        "Company" => $order->organization_name ?? 'No Organization',  // Organization (if any)
                        "Lead_Source" => 'website',  // Source of the lead
                        "Lead_Status" => 'New',      // Status of the lead (New by default)
                        "Industry" => 'agency',     // Industry type (hardcoded as 'agency' for now)
                        "Website" => 'https://alkathirimotors.com.sa/', // Website URL
                        "City" => $order->city_name ?? 'Unknown City',  // City
                        "Description" => $description ?? 'No description',  // Description of the lead
                        // "Model Year" => $order->car->year ?? 2024,  // Model Year (adjust dynamically)
                        // "Car Model" => $order->car->model_name ?? '-',  // Car Model
                        // "Purchase style" => $order->payment_type ?? '-', // Purchase Style (cash/finance)
                        "Lead_Type" => 'Warm',  // Lead type (Hot, Warm, Cold)
                        "Created_Time" => $order->created_at->toIso8601String() ?? now()->toIso8601String(), // Lead creation time
                        "Modified_Time" => $order->updated_at->toIso8601String() ?? now()->toIso8601String(), // Lead modified time
                    ]
                ]
            ];

            // Send the data to Zoho CRM using the Zoho API
            $response = Http::withToken($token->access_token)
                            ->acceptJson()  // Ensure JSON response is accepted
                            ->post('https://www.zohoapis.com/crm/v2/Leads', $orderData);

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
