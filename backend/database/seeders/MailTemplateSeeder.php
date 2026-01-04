<?php

namespace Database\Seeders;

use App\Models\MailTemplate;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * 30日講座のメールテンプレートをシード
     *
     * Complete30day_email_full.md からパースして挿入
     */
    public function run(): void
    {
        $filePath = base_path('../docs/Complete30day_email_full.md');

        if (!file_exists($filePath)) {
            $this->command->error('メールコンテンツファイルが見つかりません: ' . $filePath);
            return;
        }

        $content = file_get_contents($filePath);
        $emails = $this->parseEmailContent($content);

        foreach ($emails as $email) {
            MailTemplate::updateOrCreate(
                ['day_number' => $email['day_number']],
                [
                    'subject' => $email['subject'],
                    'body' => $email['body'],
                    'is_active' => true,
                ]
            );

            $this->command->info("Day {$email['day_number']}: {$email['subject']}");
        }

        $this->command->info('');
        $this->command->info(count($emails) . '件のメールテンプレートをインポートしました。');
    }

    /**
     * マークダウンファイルをパースしてメール内容を抽出
     */
    private function parseEmailContent(string $content): array
    {
        $emails = [];

        // Day 0-30 + Day 14.5 のパターンでマッチ
        $pattern = '/^#{2,3}\s+Day\s+(\d+(?:\.\d+)?)[^\n]*\n(.*?)(?=^#{2,3}\s+Day|\z)/ms';

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $dayNumber = $match[1];
                $dayContent = $match[2];

                // Day 29-30 セクションをスキップ（個別のDayは後で取得される）
                if ($dayNumber === '29-30') {
                    continue;
                }

                // コードブロック内のコンテンツを抽出
                if (preg_match('/```\n(.*?)\n```/s', $dayContent, $codeMatch)) {
                    $emailText = $codeMatch[1];

                    // 件名を抽出
                    $subject = '';
                    if (preg_match('/^件名[：:]\s*(.+)$/m', $emailText, $subjectMatch)) {
                        $subject = trim($subjectMatch[1]);
                    }

                    // 本文を抽出（件名の次の行から）
                    $body = preg_replace('/^件名[：:].*\n\n?/m', '', $emailText);
                    $body = trim($body);

                    // Day番号を整数または小数で保存（14.5など）
                    $dayNum = strpos($dayNumber, '.') !== false
                        ? (float) $dayNumber
                        : (int) $dayNumber;

                    $emails[] = [
                        'day_number' => $dayNum,
                        'subject' => $subject,
                        'body' => $body,
                    ];
                }
            }
        }

        // day_numberでソート
        usort($emails, fn($a, $b) => $a['day_number'] <=> $b['day_number']);

        return $emails;
    }
}
