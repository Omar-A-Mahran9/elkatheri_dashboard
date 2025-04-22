<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
  use App\Models\CampaignVisit;
 use Illuminate\Http\Request;

class CampaignsResultController extends Controller
{
    public function show(Request $request, $id)
    {
          $count_campaign = CampaignVisit::where('campaign_id',$request->campaign_id)->count(); // Get the count of blogs
        dd( $count_campaign,$request);
          $this->authorize('view_campaign');
         if ($request->ajax())
        {
            $data = getModelData( model: new CampaignVisit(), andsFilters: [ ['campaign_id', '=', $request->get('campaign_id')] ],relations:['campaign' => [ 'id' , 'campaign_name' ] ] );

            return response()->json($data);
        }

        return view('dashboard.campaigns.show', compact('count_campaign', 'id'));
    }
}
