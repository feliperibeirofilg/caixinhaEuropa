<?php

namespace App\Providers;

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
        // Se estiver em produção (Render), força HTTPS em tudo
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
