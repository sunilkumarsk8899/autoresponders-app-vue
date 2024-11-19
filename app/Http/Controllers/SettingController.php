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


    /** store settings data */
    public function store_setting(Request $request){
        $combinedArray = [];
        try {
            if ($request->input('type') == 'clickbank') {
                // Validate the request data for clickbank
                $validatedData = $request->validate([
                    'clickbank_apiurl' => 'required|array',
                    'clickbank_apiurl.*' => 'required|url',
                    'clickbank_captain_key' => 'required|array',
                    'clickbank_captain_key.*' => 'required|string',
                ]);

                foreach ($request->clickbank_apiurl as $index => $apiurl) {
                    if (isset($request->clickbank_captain_key[$index])) {
                        $combinedArray[] = [
                            'url' => $apiurl,
                            'key' => $request->clickbank_captain_key[$index]
                        ];
                    }
                }
            } elseif ($request->input('type') == 'activecampaign') {
                // Validate the request data for activecampaign
                $validatedData = $request->validate([
                    'activecampaign_apiurl' => 'required|array',
                    'activecampaign_apiurl.*' => 'required|url',
                    'activecampaign_apikey' => 'required|array',
                    'activecampaign_apikey.*' => 'required|string',
                    'activecampaign_list_id' => 'required|array',
                    'activecampaign_list_id.*' => 'required|integer',
                ]);

                foreach ($request->activecampaign_apiurl as $index => $apiurl) {
                    if (isset($request->activecampaign_apikey[$index])) {
                        $combinedArray[] = [
                            'url' => $apiurl,
                            'key' => $request->activecampaign_apikey[$index],
                            'list_id' => $request->activecampaign_list_id[$index]
                        ];
                    }
                }
            }

            // Delete old settings and insert new ones
            $deleted = Setting::where('name', $request->input('type'))->delete();
            foreach ($combinedArray as $dets) {
                $insertData = [
                    'name' => $request->input('type'),
                    'info' => serialize($dets),
                ];
                Setting::create($insertData);
            }

            return response()->json([
                'data' => $request->all(),
                'combine_arr' => $combinedArray,
                'validation' => $validatedData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors as a JSON response
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Return generic errors
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /** get all settings data by name */
    public function get_settings($name)
    {
        // Get the settings based on the name
        $settings = Setting::where('name', $name)->get();

        // Check if settings exist
        if ($settings->isNotEmpty()) {
            // Process each setting to unserialize the 'info' column
            $settings = $settings->map(function($setting) {
                $unserializedData = unserialize($setting->info);
                return [
                    'url' => $unserializedData['url'] ?? '', // Assuming 'url' exists in unserialized data
                    'key' => $unserializedData['key'] ?? '',  // Assuming 'key' exists in unserialized data
                    'id' => $setting->id,
                    'list_id' => $unserializedData['list_id'] ?? ''
                ];
            });

            // Return the unserialized data as JSON
            return response()->json([
                'data' => $settings
            ]);
        }

        // If no settings found, return an error response
        return response()->json(['error' => 'Settings not found'], 404);
    }



    /** delete settings */
    public function delete_setting(Request $request){
        $id = $request->input('id'); // Ensure you are getting the ID from the request body
        $setting = Setting::find($id);
        if ($setting) {
            $setting->delete();
            return response()->json(['message' => 'Setting deleted successfully']);
        } else {
            return response()->json(['message' => 'Setting not found'], 404);
        }
    }




    /** get clickbank accounts */
    public function get_account($type){
        $clickbankaccount = Setting::where('name',$type)->get();

        if(!$clickbankaccount){
            return response()->json([
                'error' => 'Clickbank account not found',
                'resp' => false
            ]);
        }

        return response()->json([
            'data' => $clickbankaccount,
            'resp' => true
        ]);
    }






}
