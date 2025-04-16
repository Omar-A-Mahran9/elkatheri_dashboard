<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewResource;
use App\Models\Campaign;
use App\Models\CampaignVisit;
use App\Models\News;
use Illuminate\Http\Request;

class NewController extends Controller
{
    public function all()
    {
        $news = NewResource::collection(News::get());

        return $news;
    }

    public function highlighted()
    {
        $news = NewResource::collection(News::whereHighlighted(true)->get());

        return $news;
    }

    public function singleNew(News $news)
    {
        return new NewResource($news);
    }

    public function geturl(Request $request)
    {
        $shortenCode = $request->input('shorten_code');

        if (empty($shortenCode)) {
            return response()->json(['error' => 'shorten_code is required'], 422);
        }

        // Find the campaign by shorten_code
        $campaign = Campaign::where('shorten_code', $shortenCode)->first();

        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        // Log the visit
        CampaignVisit::create([
            'campaign_id' => $campaign->id,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        // Return the website_url_new
        return response()->json([
            'website_url_new' => $campaign->website_url_new
        ]);
    }
}
