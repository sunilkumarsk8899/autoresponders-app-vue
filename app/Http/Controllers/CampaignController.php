<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function add_campaign(){
        $settings = [
            'title' => 'My Campaign'
        ];

        return Inertia::render('Admin/AddCampaign', [
            'settings' => $settings,
            'user' => Auth::user(),
        ]);
    }
}
