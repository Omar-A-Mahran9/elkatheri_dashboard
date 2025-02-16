<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignVisit;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function checkCampaign($code, Request $request)
    {
        // Find campaign by code
        $campaign = Campaign::where('shorten_code', $code)->first();

        if (!$campaign) {
            abort(404, 'Campaign not found');
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
        }

        // Redirect to the campaign URL
        return redirect()->away($campaign->website_url_new);
    }
}
