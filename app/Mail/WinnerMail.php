<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WinnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    /**
     * Create a new message instance.
     */
    public function __construct($auction)
    {
        $this->auction = $auction;
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('messages.winner_mail_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auctions.winner-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        \Log::info('Sending email to Auction owner');
        \Log::info('Using view: eemails.auctions.winner-mail');

        return $this->view('emails.auctions.winner-mail')
            ->with([
                'title' => $this->auction->title
            ]);
    }
}
