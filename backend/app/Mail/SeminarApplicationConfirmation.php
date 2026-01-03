<?php

namespace App\Mail;

use App\Models\SeminarApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarApplicationConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SeminarApplication $application,
        public array $settings = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【Save My 12 Weeks】お申込みありがとうございます！',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seminar.confirmation',
        );
    }
}
