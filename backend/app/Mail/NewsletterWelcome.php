<?php

namespace App\Mail;

use App\Models\MailSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public MailSubscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【Save My 12 Weeks】30日講座へようこそ！',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.welcome',
        );
    }
}
