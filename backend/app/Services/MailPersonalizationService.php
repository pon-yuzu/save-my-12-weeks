<?php

namespace App\Services;

use App\Models\MailSubscription;
use App\Models\SeminarSetting;

class MailPersonalizationService
{
    /**
     * 診断結果の領域IDを日本語ラベルに変換
     */
    private const AREA_LABELS = [
        'health' => '健康・体',
        'mind' => '心の平穏',
        'money' => 'お金',
        'career' => '仕事・キャリア',
        'time' => '自分の時間',
        'living' => '暮らし・環境',
        'relationships' => '人間関係',
        'vision' => '将来・ビジョン',
    ];

    /**
     * テンプレート本文の変数を置換
     */
    public function personalize(string $content, MailSubscription $subscription): string
    {
        $variables = $this->buildVariables($subscription);

        foreach ($variables as $key => $value) {
            $content = str_replace('${' . $key . '}', $value, $content);
        }

        return $content;
    }

    /**
     * 件名の変数を置換
     */
    public function personalizeSubject(string $subject, MailSubscription $subscription): string
    {
        return $this->personalize($subject, $subscription);
    }

    /**
     * 変数のマッピングを構築
     */
    private function buildVariables(MailSubscription $subscription): array
    {
        $diagnosisResult = $subscription->diagnosisResult;

        return [
            // 配信停止URL
            'unsubscribe_url' => route('unsubscribe.show', $subscription->token),

            // 診断で選んだ「変えたい項目」
            'areas_to_change' => $this->formatAreasToChange($diagnosisResult?->selected_areas),

            // セミナー申込URL
            'seminar_application_url' => url('/seminar'),

            // 現在のDay
            'day_number' => (string) $subscription->current_day,

            // セミナー設定から取得
            'next_seminar_date' => $this->getSeminarSetting('schedule', '日程未定'),
            'zoom_url' => $this->getSeminarSetting('zoom_url', ''),
            'line_openchat_url' => $this->getSeminarSetting('line_openchat_url', ''),
            'participation_code' => $this->getSeminarSetting('participation_code', ''),
        ];
    }

    /**
     * 選択された領域を日本語に変換してフォーマット
     */
    private function formatAreasToChange(?array $areas): string
    {
        if (empty($areas)) {
            return 'あなたの気になる領域';
        }

        $labels = array_map(
            fn($area) => self::AREA_LABELS[$area] ?? $area,
            $areas
        );

        return implode('・', $labels);
    }

    /**
     * セミナー設定を取得
     */
    private function getSeminarSetting(string $key, string $default = ''): string
    {
        $setting = SeminarSetting::where('key', $key)->first();
        return $setting?->value ?? $default;
    }
}
