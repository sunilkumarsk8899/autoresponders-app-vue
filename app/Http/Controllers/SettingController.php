<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingController extends Controller
{
    /** home page */
    public function index(){
        $settings = [
            'title' => 'My Settings'
        ];

        return Inertia::render('Admin/GetResponse', [
            'settings' => $settings,
            'user' => Auth::user(),
        ]);
    }

    /** get response page */
    public function getresponse(){
        $settings = [
            'title' => 'Get Response'
        ];

        return Inertia::render('Admin/GetResponse', [
            'settings' => $settings
        ]);
    }

    /** clickbank page */
    public function clickbank(){
        $settings = [
            'title' => 'Click Bank'
        ];

        return Inertia::render('Admin/ClickBank', [
            'settings' => $settings
        ]);
    }


    /** active campaign page */
    public function activecampaign(){
        $settings = [
            'title' => 'Active Campaign'
        ];

        return Inertia::render('Admin/ActiveCampaign', [
            'settings' => $settings
        ]);
    }

    public function store_setting(Request $request){

        if($request->input('type') == 'clickbank'){
            // Validate the request data
            $validatedData = $request->validate([
                'clickbank_captain_key' => 'required',
                'clickbank_apiurl' => 'required'
            ]);
        }

        // Create a new combined array
        $combinedArray = [];
        foreach ($request->clickbank_apiurl as $index => $apiurl) {
            if (isset($request->clickbank_captain_key[$index])) {
                $combinedArray[] = ['url' => $apiurl, 'key' => $request->clickbank_captain_key[$index]];
            }
        }

        foreach($combinedArray as $dets){
            $insertData = [
                'name' => $request->input('type'),
                'info' => serialize($dets),
            ];
            Setting::create($insertData);
        }

        return response()->json([
            'data' => $request->all(),
            'combine_arr' => $combinedArray
        ]);
    }
}
