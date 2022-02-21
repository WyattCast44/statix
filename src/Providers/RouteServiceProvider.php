<?php

namespace Statix\Providers;

use Illuminate\Routing\Router;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Statix\Routing\RouteRegistrar;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->app->singleton(RouteRegistrar::class, function() {
            return new RouteRegistrar;
        });
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

        $this->app->routes = $this->app->make(RouteRegistrar::class);
    }
}