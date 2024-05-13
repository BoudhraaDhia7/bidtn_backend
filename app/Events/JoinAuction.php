<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoinAuction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $currentBid;
    public $currentBidderName;
    public $remainingUserBalance;
    public $auctionId;
    
    /**
     * Create a new event instance.
     */
    public function __construct($auctionId ,$currentBid, $currentBidderName, $remainingUserBalance)
    {   
        $this->auctionId = $auctionId;
        $this->currentBid = $currentBid;
        $this->currentBidderName = $currentBidderName;
        $this->remainingUserBalance = $remainingUserBalance;
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
        return 'join.auction';
    }


}
