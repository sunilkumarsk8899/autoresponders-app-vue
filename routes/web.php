<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings',[SettingController::class,'index'])->name('settings.index');
    Route::get('/settings/clickbank',[SettingController::class,'clickbank'])->name('settings.clickbank');
    Route::get('/settings/active-campaign',[SettingController::class,'activecampaign'])->name('settings.activecampaign');
    Route::get('/campaign/add',[CampaignController::class,'add_campaign'])->name('campaign.add');
    Route::get('/campaign/{id}/edit',[CampaignController::class,'edit_campaign'])->name('campaign.edit');
    Route::get('/campaign/{camid}/start', [CampaignController::class,'campaign_start'])->name('campaign.start');

    Route::get('/admin/register', [AdminController::class, 'showRegistrationForm'])->name('admin.index');

    Route::get('/admin/user/{id}/edit', [AdminController::class, 'editUser'])->name('admin.edit');
});

Route::get('/no-access', function(){
    return Inertia::render('NotAllowed');
})->name('no-access');

require __DIR__.'/auth.php';
