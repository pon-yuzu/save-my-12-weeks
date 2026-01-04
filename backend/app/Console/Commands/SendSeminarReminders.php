<?php

namespace App\Console\Commands;

use App\Mail\SeminarReminder;
use App\Models\Seminar;
use App\Models\SeminarApplication;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSeminarReminders extends Command
{
    protected $signature = 'seminars:send-reminders';
    protected $description = 'セミナーのリマインドメールを送信';

    public function handle(): int
    {
        $now = Carbon::now('Asia/Tokyo');
        $this->info("リマインドメール送信開始: {$now}");

        // 前日リマインド（21:00に送信）
        $this->sendDayBeforeReminders($now);

        // 1時間前リマインド
        $this->sendOneHourReminders($now);

        $this->info('リマインドメール送信完了');
        return Command::SUCCESS;
    }

    /**
     * 前日21:00のリマインド
     */
    private function sendDayBeforeReminders(Carbon $now): void
    {
        // 21:00台のみ実行
        if ($now->hour !== 21) {
            return;
        }

        // 明日開催のセミナーを取得
        $tomorrow = $now->copy()->addDay()->startOfDay();
        $tomorrowEnd = $tomorrow->copy()->endOfDay();

        $seminars = Seminar::where('is_active', true)
            ->whereBetween('scheduled_at', [$tomorrow, $tomorrowEnd])
            ->get();

        foreach ($seminars as $seminar) {
            $applications = SeminarApplication::where('seminar_id', $seminar->id)
                ->whereNull('reminder_1day_sent_at')
                ->get();

            foreach ($applications as $application) {
                try {
                    Mail::to($application->email)->queue(
                        new SeminarReminder($application, $seminar, '1day')
                    );

                    $application->update(['reminder_1day_sent_at' => now()]);
                    $this->info("前日リマインド送信: {$application->email}");
                } catch (\Exception $e) {
                    $this->error("送信失敗: {$application->email} - {$e->getMessage()}");
                }
            }
        }
    }

    /**
     * 1時間前のリマインド
     */
    private function sendOneHourReminders(Carbon $now): void
    {
        // 1時間後に開始するセミナーを取得
        $oneHourLater = $now->copy()->addHour();
        $windowStart = $oneHourLater->copy()->subMinutes(5);
        $windowEnd = $oneHourLater->copy()->addMinutes(5);

        $seminars = Seminar::where('is_active', true)
            ->whereBetween('scheduled_at', [$windowStart, $windowEnd])
            ->get();

        foreach ($seminars as $seminar) {
            $applications = SeminarApplication::where('seminar_id', $seminar->id)
                ->whereNull('reminder_1hour_sent_at')
                ->get();

            foreach ($applications as $application) {
                try {
                    Mail::to($application->email)->queue(
                        new SeminarReminder($application, $seminar, '1hour')
                    );

                    $application->update(['reminder_1hour_sent_at' => now()]);
                    $this->info("1時間前リマインド送信: {$application->email}");
                } catch (\Exception $e) {
                    $this->error("送信失敗: {$application->email} - {$e->getMessage()}");
                }
            }
        }
    }
}
