<?php

namespace App\Mail;

use App\Models\SeminarApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarApplicationNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SeminarApplication $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【セミナー申込通知】新規申込がありました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seminar.notification',
        );
    }
}
