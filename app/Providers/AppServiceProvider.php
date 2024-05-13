<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Blueprint::macro('softDeletesBigInteger', function ($column = 'deleted_at', $precision = 0) {
            return $this->unsignedBigInteger($column, $precision)->nullable();
        });
    }
}
