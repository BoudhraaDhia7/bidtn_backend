<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;       
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('reset_password_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.users.reset-password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        $resetUrl = config('app.front_url') . '/auth/reset-password?token=' . $this->token;

        return $this->subject(__('messages.set_password_subject'))
            ->markdown('emails.users.reset-password')
            ->with([
                'resetUrl' => $resetUrl,
                'email' => $this->email,
            ]);
    }
}
