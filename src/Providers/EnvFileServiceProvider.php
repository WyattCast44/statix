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
        if(file_exists($path = $this->app->basePath() . '/.env')) {
            (Dotenv::createImmutable($this->app->basePath()))->safeLoad();
            
            event(new EnvFileLoaded);
        }
    }
}