<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FailedToStartAuctionEmail extends Mailable
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
            subject: __('messages.failed_to_start_auction_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auctions.failed-to-start-auction',
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::info('Sending email to Auction owner');
        \Log::info('Using view: eemails.auctions.failed-to-start-auction');

        return $this->view('emails.auctions.failed-to-start-auction')
            ->with([
                'title' => $this->auction->title,
            ]);
    }
}
