<?php

namespace App\Console\Commands;

use App\Http\Controllers\CampaignController;
use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Autoresp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:autoresp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Cron job executed successfully!");
        $CampaignController = new CampaignController();
        $campaigns = Campaign::where('start',1)->where('status',1)->get();
        $allResponses = [];
        foreach( $campaigns as $key => $camp ) {
            $details = unserialize($camp->details);
            $api_id = $details['clickbank_id'];
            $vendor_name = $details['clickbank_account_name'];
            $items = explode(",",$details['selected_clickbank_products_name']);
            $allResponses[$key] = [$CampaignController->getClickBankAllOrders($api_id,$vendor_name,$items),$details['active_campaign_list_id'],$vendor_name,$items];
        }



        $loggedEmails = []; // Array to keep track of unique emails
        $active_campaign_resp = [];
        $final_data = [];

        /*foreach ($allResponses as $response) {
            foreach ($response as $itemName => $itemData) {
                // Check if orderData exists and is an array
                if (isset($itemData['orderData']) && is_array($itemData['orderData'])) {
                    foreach ($itemData['orderData'] as $order) {
                        // Check for valid email
                        if (isset($order['email']) && !empty($order['email']) && !is_array($order['email'])) {
                            $email = $order['email'];

                            // Log email only if it hasn't been logged before
                            if (!in_array($email, $loggedEmails)) {
                                $loggedEmails[] = $email;

                                $active_campaign_resp = $CampaignController->addContactToActiveCampaignList();

                                $final_data[$email] = [
                                    'email' => $email,
                                    'firstName' => $order['firstName'],
                                    'lastName' => $order['lastName']
                                ]; // Add to the list of logged emails
                            }
                        }
                    }
                }
            }
        }*/



        foreach ($allResponses as $response) {
            $listId = $response[1]; // Extract list ID from response
            $vendor_name = $response[2];
            $items = $response[3];

            foreach ($response[0] as $itemName => $itemData) {
                // Check if orderData exists and is an array
                if (isset($itemData['orderData']) && is_array($itemData['orderData'])) {
                    foreach ($itemData['orderData'] as $order) {
                        // Check for valid email
                        if (isset($order['email']) && !empty($order['email']) && !is_array($order['email'])) {
                            $email = $order['email'];

                            // Log email only if it hasn't been logged before
                            if (!in_array($email, $loggedEmails)) {
                                $loggedEmails[] = $email;
                                $firstName = $order['firstName'] ?? '';
                                $lastName = $order['lastName'] ?? '';

                                $final_data[$email] = [
                                    'email' => $email,
                                    'firstName' => $firstName,
                                    'lastName' => $lastName,
                                    'list_id' => $listId,
                                    'vendor_name' => $vendor_name,
                                    'items' => $items
                                ];

                                // Call the function to add contact
                                // $active_campaign_resp[] = $CampaignController->addContactToActiveCampaignList($listId,$email,$firstName,$lastName);

                            }
                        }
                    }
                }
            }
        }



        // Log::info(print_r($active_campaign_resp,true));
        Log::info(print_r($final_data,true));





    }
}
