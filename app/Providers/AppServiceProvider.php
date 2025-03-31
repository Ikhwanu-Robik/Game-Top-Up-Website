<?php

namespace App\Providers;

use App\Models\User;
use App\View\Components\GameCard;
use Illuminate\Support\Facades\Gate;
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
        
        Gate::define('view-admin-nav', function (User $user) {
            return $user->isAdministrator();
        });
        Gate::define('view-create-package', function (User $user) {
            return $user->isAdministrator();
        });
        Gate::define('view-create-game', function (User $user) {
            return $user->isAdministrator();
        });
        Gate::define('create-package', function (User $user) {
            return $user->isAdministrator();
        });
        Gate::define('create-game', function (User $user) {
            return $user->isAdministrator();
        });
    }
}