<?php

namespace App\Jobs;

use App\Mail\DailyCourseEmail;
use App\Models\MailDelivery;
use App\Models\MailSubscription;
use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyCourseEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public MailSubscription $subscription
    ) {}

    public function handle(): void
    {
        // アクティブでなければスキップ
        if (!$this->subscription->is_active) {
            return;
        }

        $dayNumber = $this->subscription->current_day;

        // 30日を超えていれば終了
        if ($dayNumber > 30) {
            return;
        }

        // テンプレートを取得
        $template = MailTemplate::where('day_number', $dayNumber)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            Log::warning("No active template found for day {$dayNumber}");
            return;
        }

        // 既に送信済みかチェック
        $alreadySent = MailDelivery::where('subscription_id', $this->subscription->id)
            ->where('template_id', $template->id)
            ->where('status', 'sent')
            ->exists();

        if ($alreadySent) {
            return;
        }

        // 配信レコードを作成
        $delivery = MailDelivery::create([
            'subscription_id' => $this->subscription->id,
            'template_id' => $template->id,
            'status' => 'pending',
        ]);

        try {
            // メール送信（配信レコードを渡してトラッキングを有効化）
            Mail::to($this->subscription->email)
                ->send(new DailyCourseEmail($this->subscription, $template, $delivery));

            // 成功時の処理
            $delivery->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // 次の日に進める
            $this->subscription->increment('current_day');

        } catch (\Exception $e) {
            // 失敗時の処理
            $delivery->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("Failed to send daily course email: " . $e->getMessage(), [
                'subscription_id' => $this->subscription->id,
                'template_id' => $template->id,
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Job failed for subscription {$this->subscription->id}: " . $exception->getMessage());
    }
}
