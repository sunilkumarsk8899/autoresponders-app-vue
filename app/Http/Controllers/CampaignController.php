<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CampaignController extends Controller
{

    /** show add campaing page */
    public function add_campaign(){
        $settings = [
            'title' => 'Add Campaign'
        ];

        return Inertia::render('Admin/AddCampaign', [
            'settings' => $settings
        ]);
    }

    /** store campaign */
    public function store_campaign(Request $request){
        $request->validate([
            'name' => 'required|unique:campaigns,name',
            'desc' => 'required'
        ]);

        $insertData = [
            'user_id' => $request->input('user_id'),
            'name' => $request->name,
            'description' => $request->desc,
        ];

        $resp = Campaign::create($insertData);

        return response()->json([
            'message' => 'Campaign added successfully',
            'resp' => $resp
        ]);
    }

    /** get campaign */
    public function get_campaigns($userid){
        $campaigns = Campaign::where('user_id', $userid)->get();
        return response()->json([
            'campaigns' => $campaigns
        ]);
    }


    public function edit_campaign(){
        $settings = [
            'title' => 'Edit Campaign'
        ];

        return Inertia::render('Admin/EditCampaign', [
            'settings' => $settings
        ]);
    }

}
