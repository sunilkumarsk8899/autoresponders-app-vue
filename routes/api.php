<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/v1/add-setting',[SettingController::class,'store_setting'])->name('setting.store');
// Route::get('/v1/get-settings',[SettingController::class,'get_settings'])->name('setting.getsettings');
Route::get('/v1/{name}/get-settings', [SettingController::class, 'get_settings'])->name('setting.getsettings');
Route::post('/v1/delete-setting', [SettingController::class, 'delete_setting'])->name('setting.delete');
Route::post('/v1/store-campaign',[CampaignController::class,'store_campaign'])->name('campaign.store');
Route::get('/v1/get-campaigns/{userid}',[CampaignController::class,'get_campaigns'])->name('campaign.get');

Route::get('/v1/get-campaigns/{campid}/single',[CampaignController::class,'get_single_campaigns'])->name('campaign.get-single');
Route::post('/v1/update/campaign', [CampaignController::class, 'update_campaign'])->name('campaign.update');
Route::delete('/v1/delete-campaign/{id}', [CampaignController::class, 'delete_campaign'])->name('campaign.delete');


/** clickbank api */
Route::get('/v1/clickbank/products/{type}/orders/{vendor}/{item}', [CampaignController::class,'getClickBankAllOrders'])->name('clickbank.get-orders'); /** get all order form curl clickbank*/
Route::get('/v1/clickbank/get/{id}/accounts', [CampaignController::class,'getClickBankAllAccounts'])->name('clickbank.get_accounts'); /** get all accounts from curl clickbank*/
Route::get('/v1/clickbank/get/{id}/products/{site}', [CampaignController::class,'get_click_bank_products'])->name('clickbank.products'); /** get all products from curl clickbank */
Route::get('/v1/get-active-campaing/lists/{id}', [CampaignController::class,'getActiveCampaingLists'])->name('settings.get_activecampaign_lists'); /** get all active-campaign lists form curl */

Route::get('/v1/get-accounts/{type}', [SettingController::class,'get_account'])->name('clickbank.get_account'); /** get all clickbank accounts */
Route::get('/v1/active-campaign-accounts', [SettingController::class,'get_active_campaign_account'])->name('active_campaign.get_active_campaign_account'); /** get all active-campaign accounts */


Route::get('/v1/api/get/data/{name}/{id}', [CampaignController::class,'get_api_data'])->name('setting.get_apis');

Route::get('/v1/start/{camid}/campaing', [CampaignController::class,'campaign_start_get_info'])->name('campaign.campaign_start_get_info');


Route::get('/test', [CampaignController::class,'get_active_campaign_info']);




