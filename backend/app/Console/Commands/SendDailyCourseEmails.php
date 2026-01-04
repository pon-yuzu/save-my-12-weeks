<?php

namespace App\Console\Commands;

use App\Jobs\SendDailyCourseEmail;
use App\Models\MailSubscription;
use Illuminate\Console\Command;

class SendDailyCourseEmails extends Command
{
    protected $signature = 'newsletter:send-daily
                            {--time= : 配信時間（例: 07:00）。指定しない場合は全員に送信}
                            {--dry-run : 実際には送信せずに対象者を表示}';

    protected $description = '30日講座のメールをアクティブな登録者に送信';

    public function handle(): int
    {
        $time = $this->option('time');

        $query = MailSubscription::where('is_active', true)
            ->where('current_day', '<=', 30);

        // 時間が指定されている場合はその時間帯のユーザーのみ
        if ($time) {
            $query->where('preferred_time', $time);
            $this->info("配信時間: {$time}");
        } else {
            $this->info("配信時間: 全員（時間指定なし）");
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
                ['ID', 'Email', 'Current Day', 'Preferred Time'],
                $subscriptions->map(fn ($s) => [$s->id, $s->email, $s->current_day, $s->preferred_time])
            );
            $this->info('--dry-run オプションのため送信はスキップしました。');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($subscriptions as $subscription) {
            SendDailyCourseEmail::dispatch($subscription);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ {$count}件のメールをキューに追加しました。");

        return Command::SUCCESS;
    }
}
