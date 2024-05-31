<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAuctionCreated extends Mailable
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
            subject: __('messages.new_auction_created_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auctions.new-auction',
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
        \Log::info('Using view: eemails.auctions.new-auction');

        return $this->view('emails.auctions.new-auction')
            ->with([
                'title' => $this->auction->title,
            ]);
    }
}
