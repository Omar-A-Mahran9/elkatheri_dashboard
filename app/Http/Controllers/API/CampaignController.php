<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CampaignController extends Controller
{
    public function checkCampaign($code, Request $request)
    {
        dd('fdfdfd');
        // Find campaign by code
        $campaign = Campaign::where('shorten_code', $code)->first();

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }
        // Get user details
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Check if this user has already visited
        $existingVisit = CampaignVisit::where('campaign_id', $campaign->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->exists();

        if (!$existingVisit) {
            // Record a new visit
            CampaignVisit::create([
                'campaign_id' => $campaign->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
               // Increment the visits count
             $campaign->increment('visits');
        }

        $campaignData = json_encode([
            'id' => $campaign->id,
            'campaign_name' => $campaign->campaign_name,
            'website_url_new' => $campaign->website_url_new,
            'shorten_link' => $campaign->shorten_link,
            'visits' => $campaign->visits,
        ]);
        $cookie = Cookie::make("campaign_data", $campaignData, 525600);
        // Redirect to the campaign URL
        return response()->json([
            'short_link' => $campaign->shorten_link,
            'main_url' => $campaign->website_url_new,
        ]);

    }

    public function trackVisit(Request $request)
    {
         // Get the full URL including query parameters
        $fullUrl = $request->fullUrl();

        // Find the campaign matching the URL
        $campaign = Campaign::where('website_url_new', $fullUrl)->first();
        dd('fdfdfd');

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        // Get user details
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Check if this user has already visited
        $existingVisit = CampaignVisit::where('campaign_id', $campaign->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->exists();

        if (!$existingVisit) {
            // Record a new visit
            CampaignVisit::create([
                'campaign_id' => $campaign->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);

            // Increment campaign visit count
            $campaign->increment('visits');
        }

        return response()->json([
            'message' => 'Visit tracked successfully',
            'campaign' => $campaign,
        ]);
    }


    public function getCampaignData(Request $request)
{
    $campaignId = 1; // Replace with the actual campaign ID
    $cookieName = "campaign_visited_" . $campaignId;

    // Retrieve the cookie
    $campaignData =  $request->cookie();

    if ($campaignData) {
        return response()->json([
            'success' => true,
            'campaign' => $campaignData
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'No campaign data found in cookies.'
        ], 404);
    }
}

}
