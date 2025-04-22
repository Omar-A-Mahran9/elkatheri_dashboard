<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
  use App\Models\CampaignVisit;
 use Illuminate\Http\Request;

class CampaignsResultController extends Controller
{
    public function show(Request $request)
    {
         $count_campaign = CampaignVisit::count(); // Get the count of blogs
         $this->authorize('view_campaign');
dd($request);
        if ($request->ajax())
        {
            $data = getModelData( model: new CampaignVisit(), andsFilters: [ [ 'campaign_id' , '=' , $request['campaign_id'] ] ],relations:['campaign' => [ 'id' , 'campaign_name' ] ] );

            return response()->json($data);
        }

        return view('dashboard.campaigns.show' ,compact('count_campaign'));
    }
}
