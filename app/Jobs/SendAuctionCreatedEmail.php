<?php

namespace App\Jobs;

use App\Mail\NewAuctionCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendAuctionCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auction;

    /**
     * Create a new job instance.
     *
     * @param $auction
     */
    public function __construct($auction)
    {
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve all users except the auction owner
        $users = User::where('id', '!=', $this->auction->user_id)->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NewAuctionCreated($this->auction));
        }
    }
}

