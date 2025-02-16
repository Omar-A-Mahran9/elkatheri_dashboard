<?php

namespace App\Http\Middleware;

use App\Models\Campaign;
use App\Models\CampaignVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TrackCampaignVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Parse URL components
  // Get the full URL exactly as it was requested
  $fullUrl = $request->getSchemeAndHttpHost();

  // Get raw query string from PHP superglobal (preserves original order)
  $queryString = $_SERVER['QUERY_STRING'] ?? '';

  if (!empty($queryString)) {
      $fullUrl .= '?' . $queryString;
  }
         // Find campaign by the full URL
        $campaign = Campaign::where('website_url_new', $fullUrl)->first();
         if ($campaign) {
            $campaignData = json_encode([
                'id' => $campaign->id,
                'campaign_name' => $campaign->campaign_name,
                'website_url_new' => $campaign->website_url_new,
                'shorten_link' => $campaign->shorten_link,
                'visits' => $campaign->visits,
            ]);

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
            $cookie = Cookie::make("campaign_data", $campaignData, 525600);
         }

        return $next($request);
    }}
