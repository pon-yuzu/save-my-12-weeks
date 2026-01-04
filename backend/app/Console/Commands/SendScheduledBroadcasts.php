<?php

namespace App\Console\Commands;

use App\Jobs\SendBroadcastEmail;
use App\Models\Broadcast;
use App\Models\BroadcastRecipient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendScheduledBroadcasts extends Command
{
    protected $signature = 'broadcasts:send-scheduled';
    protected $description = '予約された時間になったブロードキャストを送信';

    public function handle(): int
    {
        $broadcasts = Broadcast::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($broadcasts->isEmpty()) {
            $this->info('予約配信なし');
            return 0;
        }

        foreach ($broadcasts as $broadcast) {
            $this->info("ブロードキャスト #{$broadcast->id} を送信開始: {$broadcast->subject}");

            // ステータスを送信中に変更
            $broadcast->update(['status' => 'sending']);

            // 対象者を取得
            $subscriptions = $broadcast->getTargetSubscriptions();

            if ($subscriptions->isEmpty()) {
                $broadcast->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                $this->warn("対象者なし");
                continue;
            }

            $this->info("対象者: {$subscriptions->count()}人");

            // 各購読者にメールをキューイング
            foreach ($subscriptions as $subscription) {
                // 受信者レコードを作成
                $recipient = BroadcastRecipient::create([
                    'broadcast_id' => $broadcast->id,
                    'subscription_id' => $subscription->id,
                    'status' => 'pending',
                ]);

                // ジョブをキューに追加
                SendBroadcastEmail::dispatch($broadcast, $subscription, $recipient);
            }

            Log::info("Scheduled broadcast #{$broadcast->id} queued for {$subscriptions->count()} recipients");
        }

        $this->info('予約配信の処理完了');
        return 0;
    }
}
