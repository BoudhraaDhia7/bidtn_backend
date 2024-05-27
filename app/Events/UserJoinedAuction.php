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


class UserJoinedAuction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $auction;

    public function __construct(User $user, Auction $auction)
    {
        $this->user = $user;
        $this->auction = $auction;
    }

    public function broadcastOn()
    {   

        NotificationRepository::saveNotification([
            'user_id' => $this->auction->user_id,
            'title' => 'New User Joined Auction',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . ' has joined ' . $this->auction->title ,
            'icon' => $this->user->getPhotoAttribute(),
            'type' => 'user_joined_auction',
        ]);
        
        return new PrivateChannel('notifications.'.$this->auction->user_id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'user_joined_auction',
            'title' => 'New user has joined your auction',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . 'Has joined' . $this->auction->title,
            'icon' => $this->user->getPhotoAttribute(),
        ];
    }

    public function broadcastAs()
    {
        return 'user.joined.auction';
    }
}
