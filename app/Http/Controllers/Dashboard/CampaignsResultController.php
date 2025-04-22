<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignResult;
use App\Models\CampaignVisit;
use App\Models\Service;
use Illuminate\Http\Request;

class CampaignsResultController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_campaign');

        if ($request->ajax())
        {
            $data = getModelData( model: new CampaignVisit() );

            return response()->json($data);
        }

        return view('dashboard.campaigns.show');
    }
}
