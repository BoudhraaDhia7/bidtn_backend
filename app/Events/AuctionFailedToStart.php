<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class AuctionFailedToStart implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;
    public $user;

    public function __construct(Auction $auction, User $user)
    {   
        $this->auction = $auction;
        $this->user = $user;
    }

    public function broadcastOn()
    {   
        NotificationRepository::saveNotification([
            'user_id' => $this->auction->user_id,
            'title' => 'Your auction has failed to start',
            'description' => 'Auction ' . $this->auction->title .' has failed to start',
            'icon' => $this->user->getPhotoAttribute(),
            'type' => 'auction_failed_to_start',
        ]);
        
        return new PrivateChannel('notifications.'.$this->auction->user_id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'auction_failed_to_start',
            'title' => 'Your auction has failed to start',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . 'Auction' . $this->auction->title .'has failed to start',
            'icon' => $this->user->getPhotoAttribute(),
        ];
    }

    public function broadcastAs()
    {
        return 'auction.failedToStart';
    }
}