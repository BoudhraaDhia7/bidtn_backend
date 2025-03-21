<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Auction;
use App\Models\JetonPack;
use App\Models\JetonTransaction;
use App\Policies\AuctionPolicy;
use App\Policies\JetonPackPolicy;
use App\Policies\JetonTransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Auction::class => AuctionPolicy::class,
        JetonPack::class => JetonPackPolicy::class,
        JetonTransaction::class => JetonTransactionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
