<?php

namespace App\Jobs;

use App\Mail\BroadcastEmail;
use App\Models\Broadcast;
use App\Models\BroadcastRecipient;
use App\Models\MailSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBroadcastEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Broadcast $broadcast,
        public MailSubscription $subscription,
        public BroadcastRecipient $recipient
    ) {}

    public function handle(): void
    {
        // アクティブでなければスキップ
        if (!$this->subscription->is_active) {
            $this->recipient->update(['status' => 'failed', 'error_message' => 'Subscription inactive']);
            return;
        }

        try {
            Mail::to($this->subscription->email)
                ->send(new BroadcastEmail($this->subscription, $this->broadcast, $this->recipient));

            $this->recipient->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // ブロードキャストのステータスを更新
            $this->updateBroadcastStatus();

        } catch (\Exception $e) {
            $this->recipient->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("Failed to send broadcast email: " . $e->getMessage(), [
                'broadcast_id' => $this->broadcast->id,
                'subscription_id' => $this->subscription->id,
            ]);

            throw $e;
        }
    }

    private function updateBroadcastStatus(): void
    {
        $broadcast = $this->broadcast->fresh();

        $totalRecipients = $broadcast->recipients()->count();
        $sentRecipients = $broadcast->recipients()->where('status', 'sent')->count();
        $failedRecipients = $broadcast->recipients()->where('status', 'failed')->count();

        // 全て送信完了したらステータスを更新
        if ($sentRecipients + $failedRecipients >= $totalRecipients) {
            $broadcast->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Broadcast email job failed for subscription {$this->subscription->id}: " . $exception->getMessage());
    }
}
