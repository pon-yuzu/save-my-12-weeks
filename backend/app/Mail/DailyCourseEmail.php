<?php

namespace App\Mail;

use App\Models\MailDelivery;
use App\Models\MailSubscription;
use App\Models\MailTemplate;
use App\Services\MailPersonalizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
}
