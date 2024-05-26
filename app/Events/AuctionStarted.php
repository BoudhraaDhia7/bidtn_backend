<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;
    public $user;

    public function __construct(Auction $auction , User $user)
    {
        $this->user = $user;
        $this->auction = $auction;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.'.$this->auction->user_id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'auction_started',
            'title' => 'Your auction has started',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . 'Auction:' . $this->auction->title . 'has started',
            'icon' => $this->user->getPhotoAttribute(),
        ];
    }


    public function broadcastAs()
    {
        return 'auction.started';
    }
}