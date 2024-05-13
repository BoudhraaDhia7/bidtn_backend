<?php

namespace App\Console\Commands;

use App\Repositories\AuctionRepository;
use Illuminate\Console\Command;

class startAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start all auctions that are ready to start';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AuctionRepository::startAuctions();
    }
}
