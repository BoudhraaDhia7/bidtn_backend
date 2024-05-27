<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\User;
use App\Repositories\NotificationRepository;
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
        NotificationRepository::saveNotification([
            'user_id' => $this->auction->user_id,
            'title' => 'Your auction has started',
            'description' =>'Auction ' . $this->auction->title .' has sarted',
            'icon' => $this->user->getPhotoAttribute(),
            'type' => 'auction_started',
        ]);
        
        return new PrivateChannel('notifications.'.$this->auction->user_id);
    }

    public function broadcastWith()
    {   

        return [
            'type' => 'auction_started',
            'title' => 'Your auction has started',
            'description' => 'Auction: ' . $this->auction->title . ' has started ',
            'icon' => $this->user->getPhotoAttribute(),
        ];
    }


    public function broadcastAs()
    {
        return 'auction.started';
    }
}