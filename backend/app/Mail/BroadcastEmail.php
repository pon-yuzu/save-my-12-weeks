<?php

namespace App\Mail;

use App\Models\Broadcast;
use App\Models\BroadcastRecipient;
use App\Models\MailSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $personalizedBody;
    public string $unsubscribeUrl;
    public ?string $trackingPixelUrl;

    public function __construct(
        public MailSubscription $subscription,
        public Broadcast $broadcast,
        public ?BroadcastRecipient $recipient = null
    ) {
        $this->personalizedBody = $this->personalizeBody($broadcast->body, $subscription);
        $this->unsubscribeUrl = route('unsubscribe.show', $subscription->token);

        // 開封トラッキング用URL
        $this->trackingPixelUrl = $recipient
            ? route('tracking.pixel', ['type' => 'broadcast', 'id' => $recipient->id])
            : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->broadcast->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.broadcast',
        );
    }

    /**
     * 本文のパーソナライズ（変数置換）
     */
    private function personalizeBody(string $body, MailSubscription $subscription): string
    {
        $replacements = [
            '${name}' => $subscription->nickname ?? 'あなた',
            '${email}' => $subscription->email,
            '${current_day}' => $subscription->current_day,
        ];

        // 診断結果がある場合
        if ($subscription->diagnosisResult) {
            $areas = $subscription->diagnosisResult->selected_areas ?? [];
            $areaNames = $this->getAreaNames($areas);
            $replacements['${areas_to_change}'] = implode('・', $areaNames) ?: '選択なし';
        }

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $body
        );
    }

    private function getAreaNames(array $areaIds): array
    {
        $areaMap = [
            'health' => '健康・体',
            'mind' => '心の平穏',
            'money' => 'お金',
            'career' => '仕事・キャリア',
            'time' => '自分の時間',
            'living' => '暮らし・環境',
            'relationships' => '人間関係',
            'vision' => '将来・ビジョン',
        ];

        return array_map(fn($id) => $areaMap[$id] ?? $id, $areaIds);
    }
}
