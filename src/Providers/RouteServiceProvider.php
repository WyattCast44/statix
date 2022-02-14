<?php

namespace Statix\Providers;

use Illuminate\Routing\Router;
use Illuminate\Events\Dispatcher;
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
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('router', function() {
            return new Router(
                new Dispatcher($this->app),
                $this->app,
            );
        });

        require path('routes') . '\web.php';
    }
}