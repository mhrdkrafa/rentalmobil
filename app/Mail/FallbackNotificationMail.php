<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FallbackNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageText;
    public $notificationType;

    /**
     * Create a new message instance.
     */
    public function __construct($messageText, $notificationType)
    {
        $this->messageText = $messageText;
        $this->notificationType = $notificationType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan AutoRent: ' . str_replace('_', ' ', strtoupper($this->notificationType)),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.fallback',
        );
    }
}
