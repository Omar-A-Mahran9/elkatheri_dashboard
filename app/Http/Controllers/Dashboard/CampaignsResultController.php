<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
  use App\Models\CampaignVisit;
use App\Models\OrderTracking;
use Illuminate\Http\Request;

class CampaignsResultController extends Controller
{
    public function show(Request $request, $id)
    {
          $count_campaign = CampaignVisit::where('campaign_id',$id)->count(); // Get the count of blogs
          $count_orders = OrderTracking::where('campaign_id',$id)->count(); // Get the count of blogs

           $this->authorize('view_campaign');
         if ($request->ajax())
        {
            $data = getModelData( model: new CampaignVisit(), andsFilters: [ ['campaign_id', '=', $id] ],relations:['campaign' => [ 'id' , 'campaign_name' ] ] );

            return response()->json($data);
        }

        return view('dashboard.campaigns.show', compact('count_campaign','count_orders', 'id'));
    }
}
