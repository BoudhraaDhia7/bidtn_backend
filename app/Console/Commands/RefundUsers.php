<?php

namespace App\Console\Commands;

use App\Repositories\AuctionRepository;
use Illuminate\Console\Command;

class RefundUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refund-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refunds users who have join a non starting auction.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AuctionRepository::refundUsers();
    }
}
