<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FinishAuction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $auctionId;
    /**
     * Create a new event instance.
     */
    public function __construct($auctionId , $userId)
    {
        $this->userId = $userId;
        $this->auctionId = $auctionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
      return new PrivateChannel('auction.'.$this->auctionId);   
    }

    public function broadcastAs()
    {
        return 'finished.auction';
    }

    public function broadcastWith()
    {
        return [
            'userId' => $this->userId,
            'auctionId' => $this->auctionId
        ];
    }

}
