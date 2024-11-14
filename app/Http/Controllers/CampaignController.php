<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Setting;
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

        $serliData = [
            'clickbank_id' => $request->input('clickbank_id'),
            'clickbank_item_id' => $request->input('clickbank_item_id'),
            'clickbank_item_site_name' => $request->input('clickbank_item_site_name'),
            'active_campaign_id' => $request->input('active_campaign_id'),
            'active_campaign_list_id' => $request->input('active_campaign_list_id')
        ];

        $insertData = [
            'user_id' => $request->input('user_id'),
            'name' => $request->name,
            'description' => $request->desc,
            'details' => serialize($serliData)
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


    public function edit_campaign($id){
        $settings = [
            'title' => 'Edit Campaign',
            'id' => $id
        ];

        return Inertia::render('Admin/EditCampaign', [
            'settings' => $settings
        ]);
    }


    /** get single campaign */
    public function get_single_campaigns($id){
        $campaign = Campaign::find($id);
        return response()->json([
            'campaign' => $campaign
        ]);
    }


    /** update campaign */
    public function update_campaign(Request $request){
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $updateData = [
            'name' => $request->name,
            'description' => $request->description
        ];
        $resp = Campaign::where('id', $request->id)->update($updateData);
        return response()->json([
            'message' => 'Campaign updated successfully',
            'resp' => $resp,
            'updatedata' => $updateData,
            'request' => $request->all()
        ]);
    }


    /** delete campaign */
    public function delete_campaign($id){
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        $campaign->delete();

        return response()->json(['message' => 'Campaign deleted successfully'], 200);
    }


    /** campaign start */
    public function campaign_start($id){
        $settings = [
            'title' => 'Campaing Start'
        ];
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }
        return Inertia::render('Admin/CampaignStart', [
            'message' => 'Campaign started successfully',
            'settings' => $settings,
            'campaign' => $campaign
        ]);
    }





    // Function to make API request
    protected function clickbankApiRequest($endpoint, $params = [], $type) {
        $url = 'https://api.clickbank.com/rest/1.3/' . $endpoint . '?' . http_build_query($params);

        if($type == 'one'){
            $CLICKBANK_CAPTAIN_KEY =  'API-XEYY0SVZ7D1H2NC2SGL7KH4R2BUKNZS8O2TM';
        }

        if($type == 'two'){
            $CLICKBANK_CAPTAIN_KEY =  'API-2AN14EZXSZA5GPU0UNOLL64L9FYY8I6432ZC';
        }

        if($type == 'three'){
            $CLICKBANK_CAPTAIN_KEY =  'API-2AN14EZXSZA5GPU0UNOLL64L9FYY8I6432ZC';

        }

        // Your ClickBank API credentials

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json',
                                                    'Connection: Keep-Alive',
                                                    'Accept: application/json',
            'Authorization: ' . $CLICKBANK_CAPTAIN_KEY
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        // Debugging output
        // echo "Request URL: $url\n";
        // echo "Response: $response\n";

        $decoded_response = json_decode($response, true);
        if (isset($decoded_response['error'])) {
            echo "API Error: " . $decoded_response['error']['message'] . "\n";
        }

        return $decoded_response;
    }

    // Fetching all orders
    public function getClickBankAllOrders($type) {
        $endpoint = 'orders/list';
        $params = [
            'startDate' => date('Y-m-d',strtotime("-1 days")),
            'endDate' => date('Y-m-d')
        ];
        $response = $this->clickbankApiRequest($endpoint, $params,$type );
        return response()->json([
            'response' => $response
        ]);
    }





    // Fetching all accounts
    public function getClickBankAllAccounts($type) {
        $endpoint = 'quickstats/accounts';
        $params = [
            'startDate' => date('Y-m-d', strtotime("-30 days")),
            'endDate' => date('Y-m-d')
        ];

        // Call the ClickBank API request method
        $response = $this->clickbankApiRequest($endpoint, [], $type);

        // Return the response in JSON format
        return response()->json([
            'response' => $response,
            'apicall' => true,
            'type' => $type,
            'params' => $params
        ]);
    }


    public function getUniqueAffiliates($type) {
        $salesData = $this->getClickBankAllAccounts($type)->original['response'];

        $affiliates = [];
        if (isset($salesData['sales']) && is_array($salesData['sales'])) {
            foreach ($salesData['sales'] as $sale) {
                if (!empty($sale['affiliate'])) {
                    $affiliates[] = $sale['affiliate'];
                }
            }
            // Get unique affiliates
            $affiliates = array_unique($affiliates);
        }

        return response()->json([
            'unique_affiliates' => $affiliates
        ]);
    }






    public function getActiveCampaingLists(){

        // Replace with your actual API key and API URL
        $apiKey = '6de79823680da63369c98a1bf0b53fcff748cd1cb92a0fc2b9aa0650ff20cc91b84ce4e9';
        $apiUrl = 'https://druckermedia.activehosted.com/api/3/lists';


        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Api-Token: ' . $apiKey
        ]);

        // Execute the request and store the response
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            // Decode and print the response
            $data = json_decode($response, true);
        }

        // Close the cURL session
        curl_close($ch);

        return response()->json([
            'data' => $data
        ]);

    }








}
