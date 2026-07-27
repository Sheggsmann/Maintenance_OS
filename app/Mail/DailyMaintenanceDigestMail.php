<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyMaintenanceDigestMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly User $manager,
        public readonly array $digest,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Maintenance Digest',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.daily-maintenance-digest',
        );
    }
}
