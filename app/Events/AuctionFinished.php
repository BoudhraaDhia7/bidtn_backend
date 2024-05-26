<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionFinished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;
    public $user;

    public function __construct(Auction $auction , User $user)
    {
        $this->auction = $auction;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.'.$this->auction->user_id);
    }


    public function broadcastWith()
    {
        return [
            'type' => 'auction_finished',
            'title' => 'Your auction has finished',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . 'Auction' . $this->auction->title .'has finished',
            'icon' => $this->user->getPhotoAttribute(),
        ];
    }

    public function broadcastAs()
    {
        return 'auction.finished';
    }
}
