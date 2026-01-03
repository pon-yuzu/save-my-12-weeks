<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 30日講座のメール配信（毎朝8時）
Schedule::command('newsletter:send-daily')
    ->dailyAt('08:00')
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/newsletter.log'));
