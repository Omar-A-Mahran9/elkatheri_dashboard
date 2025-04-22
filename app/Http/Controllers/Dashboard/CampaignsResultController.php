<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
  use App\Models\CampaignVisit;
 use Illuminate\Http\Request;

class CampaignsResultController extends Controller
{
    public function show(Request $request)
    {
        // Get the count of all campaign visits
        $count_campaign = CampaignVisit::count();

        // Ensure the user is authorized to view campaigns
        $this->authorize('view_campaign');

        // If it's an AJAX request, return JSON data
        if ($request->ajax()) {
            // Get campaign_id from the request if it's provided
            $campaign_id = $request->get('campaign_id');

            // Build the query for CampaignVisit, filtered by campaign_id if available
            $query = CampaignVisit::query();

            if ($campaign_id) {
                // Filter by campaign_id if it's passed in the request
                $query->where('campaign_id', $campaign_id);
            }

            // Fetch the data with the related 'campaign' data
            $data = getModelData(
                model: $query,
                relations: ['campaign' => ['id', 'campaign_name']]
            );

            // Return the data in JSON format
            return response()->json($data);
        }

        // Otherwise, load the view and pass necessary data
        return view('dashboard.campaigns.show', compact('count_campaign'));
    }

}
