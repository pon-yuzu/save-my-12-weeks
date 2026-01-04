<?php

namespace App\Mail;

use App\Models\MailSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NewsletterWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $settingsUrl;

    public function __construct(
        public MailSubscription $subscription
    ) {
        $this->settingsUrl = route('settings.time', $subscription->settings_token);
    }

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

    /**
     * ホイール画像を添付
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        $diagnosisResult = $this->subscription->diagnosisResult;

        if ($diagnosisResult?->wheel_image_path) {
            $path = Storage::disk('public')->path($diagnosisResult->wheel_image_path);

            if (file_exists($path)) {
                $attachments[] = Attachment::fromPath($path)
                    ->as('your_life_wheel.png')
                    ->withMime('image/png');
            }
        }

        return $attachments;
    }
}
