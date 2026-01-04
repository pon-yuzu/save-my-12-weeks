<?php

namespace App\Mail;

use App\Models\Seminar;
use App\Models\SeminarApplication;
use App\Models\SeminarFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarFollowup extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SeminarApplication $application,
        public Seminar $seminar,
        public SeminarFeedback $feedback
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【ご参加ありがとうございました】Save My 12 Weeks セミナー',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seminar.followup',
        );
    }
}
