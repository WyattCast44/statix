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
        // refactor to allow for .env.prod, .env.testing, etc
        if(file_exists($path = getcwd() . '/.env')) {
            (Dotenv::createImmutable(getcwd()))->safeLoad();
            
            event(new EnvFileLoaded);
        }
    }
}