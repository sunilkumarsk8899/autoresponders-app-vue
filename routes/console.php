<?php

use App\Console\Commands\Autoresp;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();



Schedule::command(Autoresp::class)->everyMinute();

// Schedule::call(function(){
//     Log::info('Schedule called');
// })->everySecond();
