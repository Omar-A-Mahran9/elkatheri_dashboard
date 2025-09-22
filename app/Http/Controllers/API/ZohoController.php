<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ZohoToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZohoController extends Controller
{
    public function redirectToZoho()
    {
        $url = "https://accounts.zoho.com/oauth/v2/auth?" . http_build_query([
            'scope' => 'ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoBooks.fullaccess.all',
            'client_id' => env('ZOHO_CLIENT_ID'),
            'response_type' => 'code',
            'access_type' => 'offline',
            'redirect_uri' => env('ZOHO_REDIRECT_URI'),
            'prompt' => 'consent',
        ]);
         return redirect($url);
    }

    public function handleZohoCallback(Request $request)
    {
        $code = $request->get('code');
         $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'code' => $code,
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'redirect_uri' => env('ZOHO_REDIRECT_URI'),
            'grant_type' => 'authorization_code',
        ]);

        $data = $response->json();
        ZohoToken::updateOrCreate(
            ['id' => 1],
            [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]
        );

        // Save access & refresh tokens to DB or cache
        // You can return or log them for now
        return response()->json($data);
    }

    public function getZohoRecords()
{
    $token = ZohoToken::first(); // استرجاع التوكن من قاعدة البيانات

    $response = Http::withToken($token->access_token)  // إرسال التوكن مع الطلب
        ->get('https://www.zohoapis.com/crm/v2/Leads');  // استعلام إلى API Zoho

    $data = $response->json();

    // يمكنك عرض البيانات أو معالجتها
    return response()->json($data);
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
            'refresh_token' => $data['refresh_token'],
            'expires_at' => now()->addSeconds($data['expires_in']),
        ]
    );

    return response()->json($data);  // إرجاع التوكنات الجديدة
}


}
