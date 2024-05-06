<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Auction;
use App\Models\AuctionParticipant;
use Illuminate\Support\Facades\DB;

class CheckAndRefundAuctions implements ShouldQueue{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Current Unix timestamp
        $currentTimestamp = now()->timestamp;

        // Get all auctions where start date has passed but not confirmed and not enough participants
        $auctions = Auction::where('start_date', '<=', $currentTimestamp)
                           ->where('is_confirmed', true)
                            ->where('is_finished', false)
                           ->get();

        foreach ($auctions as $auction) {
            $participantsCount = AuctionParticipant::where('auction_id', $auction->id)
                                                   ->where('is_refunded', false)
                                                   ->count();
            
            if ($participantsCount < $auction->starting_user_number) {
                $participants = AuctionParticipant::where('auction_id', $auction->id)
                                                  ->where('is_refunded', false)
                                                  ->get();
                
                foreach ($participants as $participant) {
                    DB::transaction(function () use ($participant) {
                        $user = $participant->user;
                        if ($user) {
                            $user->balance += $participant->paid_amount;
                            $user->save();

                            $participant->is_refunded = true;
                            $participant->should_refund = true;
                            $participant->save();

                            \Log::info("Refunded {$participant->paid_amount} to user {$user->id} for auction {$participant->auction_id}");
                        }
                    });
                }
            }
        }
    }
}
