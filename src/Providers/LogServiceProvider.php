<?php

namespace Statix\Providers;

use Illuminate\Log\Logger;
use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('log', function() {
            $log = new Logger(new MonoLogger('Statix Logger'));
            $log->pushHandler(new StreamHandler($this->app->make('paths')->get('log_path')));
            return $log;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}