<?php

namespace App\Mail;

use App\Models\MailSubscription;
use App\Models\MailTemplate;
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

    public function __construct(
        public MailSubscription $subscription,
        public MailTemplate $template
    ) {
        $this->unsubscribeUrl = route('unsubscribe.show', $subscription->token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->template->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.daily-course',
        );
    }
}
