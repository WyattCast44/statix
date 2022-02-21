<?php

namespace Statix\Providers;

use Statix\Events\ConfigBound;
use Illuminate\Config\Repository;
use Statix\Actions\LoadConfigFiles;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('config', function() {
            return new Repository;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->config = $this->app->make('config');
        
        event(new ConfigBound($this->app->make('config')));

        app()->make(LoadConfigFiles::class)->execute();
    }
}