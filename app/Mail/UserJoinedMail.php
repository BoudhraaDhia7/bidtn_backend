<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserJoinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $joinedUsers;

    /**
     * Create a new message instance.
     */
    public function __construct($auction , $joinedUsers)
    {
        $this->auction = $auction;
        $this->joinedUsers = $joinedUsers;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('messages.new_joined_user_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auctions.joined-user',
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
        \Log::info('Using view: eemails.auctions.joined-user');

        return $this->view('emails.auctions.joined-user')
            ->with([
                'total' => $this->auction->starting_user_number,
                'joinedUsers' => $this->joinedUsers,
            ]);
    }
}
