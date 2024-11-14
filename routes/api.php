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



