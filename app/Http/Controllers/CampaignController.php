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
            'clickbank_account_name' => $request->input('clickbank_account_name'),
            'selected_clickbank_products_name' => implode(",",$request->input('selected_clickbank_products_name')),
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





    protected function get_api_data($name,$id){
        $apiData = Setting::select('info')->where('name',$name)->where('id',$id)->first();
        $resp = unserialize($apiData->info);
        if($apiData->count() > 0){
            return [
                'key' => $resp['key'],
                'url' => $resp['url']
            ];
        } else {
            return [ 'resp' => 'Api Records not found!' ];
        }
    }




    // Function to make API request
    protected function clickbankApiRequest($endpoint, $params = [], $api_id) {
        $url = 'https://api.clickbank.com/rest/1.3/' . $endpoint . '?' . http_build_query($params);

        $CLICKBANK_CAPTAIN_KEY = $this->get_api_data('clickbank',$api_id)['key'];


        /*if($type == 'one'){
            $CLICKBANK_CAPTAIN_KEY =  'API-XEYY0SVZ7D1H2NC2SGL7KH4R2BUKNZS8O2TM';
        }

        if($type == 'two'){
            $CLICKBANK_CAPTAIN_KEY =  'API-2AN14EZXSZA5GPU0UNOLL64L9FYY8I6432ZC';
        }

        if($type == 'three'){
            $CLICKBANK_CAPTAIN_KEY =  'API-2AN14EZXSZA5GPU0UNOLL64L9FYY8I6432ZC';
        }*/

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

    /**================================== Fetching orders by vendor & item  ==================================*/
    public function getClickBankAllOrders($api_id,$vendor,$items) {
        $endpoint = 'orders/list';
        $allResponses = [];
        foreach ($items as $key => $item) {
            $params = [
                'startDate' => date('Y-m-d', strtotime("-5 days")),
                'endDate' => date('Y-m-d'),
                'vendor' => $vendor,
                'item' => $item
            ];
            $response = $this->clickbankApiRequest($endpoint, $params, $api_id);
            $allResponses[$key] = $response; // Store response by item name/key
        }
        return $allResponses;
    }


    /**=============================== Fetching all accounts ===============================*/
    public function getClickBankAllAccounts($api_id) {
        $endpoint = 'quickstats/accounts';
        // $endpoint = 'quickstats/list';

        // Call the ClickBank API request method
        $response = $this->clickbankApiRequest($endpoint, [], $api_id);

        // Return the response in JSON format
        return response()->json([
            'response' => $response,
            'apicall' => true,
            'api_id' => $api_id
        ]);
    }


    /** get all products by account */
    public function get_click_bank_products($api_id,$site){
        $endpoint = 'products/list';
        $params = [
            'site' => $site
        ];
        $response = $this->clickbankApiRequest($endpoint, $params,$api_id);
        return response()->json([
            'response' => $response,
            'apicall' => true,
            'api_id' => [$api_id, $params],
        ]);
    }





    // public function test($api_id,$site){
    //     $endpoint = 'products/faith01';
    //     $params = [
    //         // 'startDate' => date('Y-m-d',strtotime("-1 days")),
    //         // 'endDate' => date('Y-m-d'),
    //         'site' => $site
    //     ];
    //     $response = $this->clickbankApiRequest($endpoint, $params,$api_id);
    //     return response()->json([
    //         'response' => $response,
    //         'apicall' => true,
    //         'api_id' => [$api_id, $params]
    //     ]);
    // }




    /**================================== Fetching products by account ==================================*/
    protected function get_clickbank_orders_by_account($api_id) {
        $endpoint = 'orders2/list';
        $params = [
            'vendor' => 'pray95'
        ];
        $response = $this->clickbankApiRequest($endpoint, $params, $api_id );
        return [ 'response' => $response ];
    }


    /*public function getUniqueAffiliates($type) {
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
    }*/






    /**================================== active campaign get lists ==================================*/
    public function getActiveCampaingLists($id){

        // Replace with your actual API key and API URL
        // $apiKey = '6de79823680da63369c98a1bf0b53fcff748cd1cb92a0fc2b9aa0650ff20cc91b84ce4e9';
        // $apiUrl = 'https://druckermedia.activehosted.com/api/3/lists';

        $apiKey = $this->get_api_data('activecampaign',$id)['key'];
        $apiUrl = $this->get_api_data('activecampaign',$id)['url'].'api/3/lists?limit=50';

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
            'data' => $data,
            'resp' => [$id,$apiKey,$apiUrl]
        ]);

    }






    public function campaign_start_get_info($camid){
        $campaign = Campaign::select('id','details')->find($camid);
        $details = unserialize($campaign->details);
        if(!$campaign){
            return response()->json([
                'msg' => 'Campaign not found! try again!',
                'resp' => false
            ]);
        }


        $orders = $this->get_clickbank_orders_by_account(43);


        return response()->json([
            'msg' => 'Campaign found',
            'resp' => $details,
            'orders' => $orders
        ]);
    }











    /** get active campaing informaiton */
    public function get_active_campaign_info(){
        $campaign = Setting::where('name','activecampaign')->where('status',1)->first();
        if(!$campaign){
            return [
                'msg' => 'Campaign not found!',
                'resp' => false
            ];
        }
        $info = unserialize($campaign->info);
        return $info;
    }




    // Function to get contact by email
    public function getContactByEmail($email,$api_url,$apiKey) {

        // $api_url = 'https://druckermedia.activehosted.com';
        // $apiKey = '6de79823680da63369c98a1bf0b53fcff748cd1cb92a0fc2b9aa0650ff20cc91b84ce4e9';



        $endpoint = $api_url . "/api/3/contacts?email=".$email;

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Api-Token: ' . $apiKey,
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        if (!empty($data['contacts'])) {
            return $data['contacts'][0]['id']; // Return the contact ID
        }
        return null; // Contact not found
    }


    // public Function to add a contact to a list
    public function addEmailToList($contact_id, $list_id, $api_url, $api_key) {

        // $api_url = 'https://druckermedia.activehosted.com';
        // $api_key = '6de79823680da63369c98a1bf0b53fcff748cd1cb92a0fc2b9aa0650ff20cc91b84ce4e9';


        $endpoint = $api_url . "/api/3/contactLists";



        $data = [
            'contactList' => [
                'list' => $list_id,
                'contact' => $contact_id,
                'status' => 1  // 1 for "active" (subscribed)
            ]
        ];

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Api-Token: ' . $api_key,
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        return isset($data['contactList']); // Return true if added to the list
    }



    public function addContactToActiveCampaignList($listId, $email, $firstName = '', $lastName = '') {

        $activeInfo = $this->get_active_campaign_info();
        $apiUrl = $activeInfo['url'];
        $apiKey = $activeInfo['key'];

        // $apiUrl = 'https://druckermedia.activehosted.com';
        // $apiKey = '6de79823680da63369c98a1bf0b53fcff748cd1cb92a0fc2b9aa0650ff20cc91b84ce4e9';

        // Check contact exist
        $contact_id = $this->getContactByEmail($email,$apiUrl,$apiKey);

        if(!empty($contact_id)){

            $this->addEmailToList($contact_id, $listId, $apiUrl, $apiKey);

        }else{

        // API endpoint to add a new contact
        $endpoint = "/api/3/contacts";

        // Contact data
        $contactData = [
            "contact" => [
                "email" => $email,
                "firstName" => $firstName,
                "lastName" => $lastName
            ]
        ];





        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Api-Token: $apiKey",
            "Content-Type: application/json",
        ]);

        // Execute cURL request and get response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            // Decode and get the contact ID from the response
            $responseDecoded = json_decode($response, true);
            $contactId = $responseDecoded['contact']['id'];

            curl_close($ch);

            // Now add the contact to the list
            return $this->addContactToList($apiUrl, $apiKey, $listId, $contactId);
        }

    }

    }

    public function addContactToList($apiUrl, $apiKey, $listId, $contactId) {
        // API endpoint to add contact to list
        $endpoint = "/api/3/contactLists";

        // Contact list data
        $contactListData = [
            "contactList" => [
                "list" => $listId,
                "contact" => $contactId,
                "status" => 1 // 1 for "active"
            ]
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactListData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Api-Token: $apiKey",
            "Content-Type: application/json",
        ]);

        // Execute cURL request and get response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            // Decode and return the response
            $responseDecoded = json_decode($response, true);
            curl_close($ch);
            return $responseDecoded;
        }
    }










}
