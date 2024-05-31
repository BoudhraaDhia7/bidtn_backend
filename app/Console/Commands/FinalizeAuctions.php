<?php

namespace App\Console\Commands;

use App\Repositories\AuctionRepository;
use Illuminate\Console\Command;

class FinalizeAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:finalize-auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finalize auctions, refund losing participants, and transfer funds to the auction owner.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AuctionRepository::finalizeAuctions();
    }
}
