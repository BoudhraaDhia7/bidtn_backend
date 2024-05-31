<?php

namespace App\Events;

use App\Repositories\NotificationRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BalanceUpdateNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    /**
     * Create a new event instance.
     */
    public function __construct($user) 
    {
        $this->user = $user;
    
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
          //save the notification in the database
          NotificationRepository::saveNotification([
            'user_id' => $this->user->id,
            'title' => 'Balance Updated',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . ' you balance has been updated',
            'icon' => $this->user->getPhotoAttribute(),
            'type' => 'blance_updated',
        ]);

        return new PrivateChannel('notifications.'.$this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'type' => 'balance_updated',
            'title' => 'Balance Updated',
            'description' => $this->user->first_name . ' ' . $this->user->lastName . ' you balance has been updated.',
            'icon' => $this->user->getPhotoAttribute(),
        ];
    }

    public function broadcastAs()
    {
        return 'balance.updated';
    }
    
}
