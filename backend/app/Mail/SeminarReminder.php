<?php

namespace App\Mail;

use App\Models\Seminar;
use App\Models\SeminarApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SeminarApplication $application,
        public Seminar $seminar,
        public string $type = '1day' // '1day' or '1hour'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === '1day'
            ? '【明日開催】Save My 12 Weeks セミナーのご案内'
            : '【まもなく開始】Save My 12 Weeks セミナー';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seminar.reminder',
        );
    }
}
