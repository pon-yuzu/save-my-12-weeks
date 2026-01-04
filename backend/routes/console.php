<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 30日講座のメール配信（時間帯別）
$deliveryTimes = ['06:00', '07:00', '08:00', '12:00', '18:00', '20:00'];

foreach ($deliveryTimes as $time) {
    Schedule::command("newsletter:send-daily --time={$time}")
        ->dailyAt($time)
        ->timezone('Asia/Tokyo')
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/newsletter.log'));
}

// 予約配信の送信（毎分チェック）
Schedule::command('broadcasts:send-scheduled')
    ->everyMinute()
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/broadcasts.log'));

// セミナーリマインドメール（毎時チェック）
Schedule::command('seminars:send-reminders')
    ->hourly()
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/seminar-reminders.log'));

// セミナーフォローアップメール（毎時チェック）
Schedule::command('seminars:send-followups')
    ->hourly()
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/seminar-followups.log'));
