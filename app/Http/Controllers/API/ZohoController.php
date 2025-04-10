<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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

        // Save access & refresh tokens to DB or cache
        // You can return or log them for now
        return response()->json($data);
    }


}
