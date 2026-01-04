<?php

namespace App\Mail;

use App\Models\MailDelivery;
use App\Models\MailSubscription;
use App\Models\MailTemplate;
use App\Services\MailPersonalizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DailyCourseEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $unsubscribeUrl;
    public string $personalizedBody;
    public string $personalizedSubject;
    public string $trackingPixelUrl;

    public function __construct(
        public MailSubscription $subscription,
        public MailTemplate $template,
        public ?MailDelivery $delivery = null
    ) {
        $personalization = new MailPersonalizationService();

        $this->unsubscribeUrl = route('unsubscribe.show', $subscription->token);
        $this->personalizedBody = $personalization->personalize($template->body, $subscription);
        $this->personalizedSubject = $personalization->personalizeSubject($template->subject, $subscription);

        // トラッキングピクセルURL
        $this->trackingPixelUrl = $delivery?->tracking_token
            ? route('mail.track', $delivery->tracking_token)
            : '';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->personalizedSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.daily-course',
        );
    }

    /**
     * Day 1のみホイール画像を添付
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Day 1のみホイール画像を添付
        if ($this->template->day_number === 1) {
            $diagnosisResult = $this->subscription->diagnosisResult;

            if ($diagnosisResult?->wheel_image_path) {
                $path = Storage::disk('public')->path($diagnosisResult->wheel_image_path);

                if (file_exists($path)) {
                    $attachments[] = Attachment::fromPath($path)
                        ->as('your_life_wheel.png')
                        ->withMime('image/png');
                }
            }
        }

        return $attachments;
    }
}
