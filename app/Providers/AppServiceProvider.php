<?php

namespace App\Providers;

use App\Models\Animal\Sponsorship;
use App\Observers\SponsorshipObserver;
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
        Sponsorship::observe(SponsorshipObserver::class);
    }
}
