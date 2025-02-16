<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Service;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_campaign');

        if ($request->ajax())
        {
            $data = getModelData( model: new Campaign(), searchingColumns: ['campaign_medium', 'campaign_source'] );

            return response()->json($data);
        }

        return view('dashboard.campaigns.index');
    }

    public function create()
    {
        $this->authorize('create_campaign');
dd(env('APP_URL'));
        return view('dashboard.campaigns.create');
    }


    public function edit(Campaign $campaign)
    {
        $this->authorize('update_campaign');

        return view('dashboard.campaigns.edit',compact('campaign'));
    }


    public function show(Campaign $campaign)
    {
        $this->authorize('show_campaign');

        return view('dashboard.campaigns.show',compact('campaign'));
    }

    public function store(Request $request)
    {
         $this->authorize('create_campaign');

         $data = $request->validate([
            'campaign_source'  => 'required|string|max:255',
            'campaign_medium'  => 'required|string|max:255',
            'campaign_name'    => 'required|string|max:255',
            'website_url_new'  => 'required|url|max:255',
            'shorten_link'     => 'nullable|url|max:255',
            'website_url'      => 'nullable|url|max:255',

        ]);

   // Extract the shorten_code from shorten_link
   if (!empty($data['shorten_link'])) {
    $parsedUrl = parse_url($data['shorten_link'], PHP_URL_PATH);
    $shortenCode = trim($parsedUrl, '/short/'); // Get the last segment

    // Store the shorten code in the data array
    $data['shorten_code'] = $shortenCode;
}

        $data['reference_id'] = "REF-" . uniqid();
        Campaign::create($data);

    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update_campaign');

        $data = $request->validate([
            'campaign_source'  => 'required|string|max:255',
            'campaign_medium'  => 'required|string|max:255',
            'campaign_name'    => 'required|string|max:255',
            'website_url_new'  => 'required|url|max:255',
            'shorten_link'     => 'nullable|url|max:255',
            'website_url'      => 'nullable|url|max:255',
        ]);

        $campaign->update($data);

        return redirect()->back()->with('success', 'Campaign updated successfully.');
    }


    public function destroy(Request $request, Campaign $campaign )
    {
        $this->authorize('delete_campaign');

        if($request->ajax())
        {
            $campaign->delete();
        }
    }
}
