<?php

namespace App\Console\Commands;

use App\Mail\DailyCourseEmail;
use App\Models\MailDelivery;
use App\Models\MailSubscription;
use App\Models\MailTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSpecialEmail extends Command
{
    protected $signature = 'newsletter:send-special
                            {day : 配信する日番号（例: 14.5）}
                            {--target-day= : この日のユーザーに送信（例: --target-day=14）}
                            {--dry-run : 実際には送信せずに対象者を表示}';

    protected $description = 'Day 14.5などの特別メールを送信（current_dayは進めない）';

    public function handle(): int
    {
        $dayNumber = (float) $this->argument('day');
        $targetDay = $this->option('target-day');

        // テンプレートを取得
        $template = MailTemplate::where('day_number', $dayNumber)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            $this->error("Day {$dayNumber} のテンプレートが見つかりません。");
            return Command::FAILURE;
        }

        $this->info("テンプレート: {$template->subject}");

        // 対象ユーザーを取得
        $query = MailSubscription::where('is_active', true);

        if ($targetDay) {
            $query->where('current_day', (int) $targetDay);
            $this->info("対象: Day {$targetDay} のユーザー");
        }

        $subscriptions = $query->get();
        $count = $subscriptions->count();

        if ($count === 0) {
            $this->info('送信対象の登録者はいません。');
            return Command::SUCCESS;
        }

        $this->info("送信対象: {$count}人");

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'Email', 'Nickname', 'Current Day'],
                $subscriptions->map(fn ($s) => [$s->id, $s->email, $s->nickname, $s->current_day])
            );
            $this->info('--dry-run オプションのため送信はスキップしました。');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $sent = 0;
        $skipped = 0;

        foreach ($subscriptions as $subscription) {
            // 既に送信済みかチェック
            $alreadySent = MailDelivery::where('subscription_id', $subscription->id)
                ->where('template_id', $template->id)
                ->where('status', 'sent')
                ->exists();

            if ($alreadySent) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // 配信レコードを作成
            $delivery = MailDelivery::create([
                'subscription_id' => $subscription->id,
                'template_id' => $template->id,
                'status' => 'pending',
            ]);

            try {
                Mail::to($subscription->email)
                    ->send(new DailyCourseEmail($subscription, $template, $delivery));

                $delivery->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                // 特別メールなのでcurrent_dayは進めない！
                $sent++;

            } catch (\Exception $e) {
                $delivery->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $this->error("\n送信失敗: {$subscription->email} - {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ 送信完了: {$sent}件, スキップ: {$skipped}件");

        return Command::SUCCESS;
    }
}
