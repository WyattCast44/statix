<?php

namespace Statix\Providers;

use Dotenv\Dotenv;
use Statix\Events\EnvFileLoaded;
use Illuminate\Support\ServiceProvider;

class EnvFileServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(file_exists($path = getcwd() . '/.env')) {
            (Dotenv::createImmutable($path))->safeLoad();

            event(new EnvFileLoaded);
        }
    }
}