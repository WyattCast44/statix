<?php

namespace Statix\Providers;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('events', function() {
            return new Dispatcher($this->app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(file_exists($path = $this->app->basePath('routes/events.php'))) {
            require_once($path);
        }
    }
}