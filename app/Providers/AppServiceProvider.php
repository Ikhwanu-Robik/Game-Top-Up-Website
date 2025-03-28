<?php

namespace App\Providers;

use App\View\Components\GameCard;
use App\View\Components\TopUpPackage;
use Illuminate\Support\Facades\Blade;
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
        if (config('app.env') == 'production') {
            \URL::forceScheme('https');
        }
        Blade::component('game-card', GameCard::class);
        Blade::component('top-up-package', TopUpPackage::class);
    }
}