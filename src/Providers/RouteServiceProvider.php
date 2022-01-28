<?php

namespace Statix\Providers;

use Statix\Events\ConfigBound;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('router', function() {
            return new Router(
                $this->app->make('events'),
                $this->app,
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require path('routes') . '\web.php';
    }
}