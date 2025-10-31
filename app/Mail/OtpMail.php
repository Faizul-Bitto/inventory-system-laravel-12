<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class OtpMail extends Mailable {

    use Queueable, SerializesModels;

    public $otp;

    public function __construct( $otp ) {
        $this->otp = $otp;
    }

    public function envelope(): Envelope {
        return new Envelope(
            subject: 'PHP Laravel Inventory System Mail OTP',
        );
    }

    public function content(): Content {
        return new Content(
            view: 'email.OtpMail',
        );
    }

    public function attachments(): array {
        return [];
    }
}
