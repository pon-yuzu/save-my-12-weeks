<?php

namespace App\Console\Commands;

use App\Mail\SeminarFollowup;
use App\Models\Seminar;
use App\Models\SeminarApplication;
use App\Models\SeminarFeedback;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSeminarFollowups extends Command
{
    protected $signature = 'seminars:send-followups';
    protected $description = 'セミナー終了後のフォローアップメールを送信';

    public function handle(): int
    {
        $now = Carbon::now('Asia/Tokyo');
        $this->info("フォローアップメール送信開始: {$now}");

        // 2時間前に終了したセミナーを対象
        // （セミナー開始時刻 + duration_minutes + 2時間）
        $twoHoursAgo = $now->copy()->subHours(2);
        $threeHoursAgo = $now->copy()->subHours(3);

        $seminars = Seminar::where('is_active', true)
            ->get()
            ->filter(function ($seminar) use ($twoHoursAgo, $threeHoursAgo) {
                $endTime = $seminar->scheduled_at->copy()->addMinutes($seminar->duration_minutes);
                return $endTime->between($threeHoursAgo, $twoHoursAgo);
            });

        foreach ($seminars as $seminar) {
            $applications = SeminarApplication::where('seminar_id', $seminar->id)
                ->whereNull('followup_sent_at')
                ->get();

            foreach ($applications as $application) {
                try {
                    // フィードバックレコードを作成
                    $feedback = SeminarFeedback::createForApplication($application);

                    // フォローアップメール送信
                    Mail::to($application->email)->queue(
                        new SeminarFollowup($application, $seminar, $feedback)
                    );

                    $application->update(['followup_sent_at' => now()]);
                    $this->info("フォローアップ送信: {$application->email}");
                } catch (\Exception $e) {
                    $this->error("送信失敗: {$application->email} - {$e->getMessage()}");
                }
            }
        }

        $this->info('フォローアップメール送信完了');
        return Command::SUCCESS;
    }
}
