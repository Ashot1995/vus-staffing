<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule profile reminders to run daily
Schedule::command('profile:send-reminders')
    ->daily()
    ->at('09:00')
    ->timezone('Europe/Stockholm')
    ->description('Send profile maintenance reminders to users every 11 months');
