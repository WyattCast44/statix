<?php

namespace Statix\Providers;

use Statix\Events\CliBound;
use Statix\Commands\MakeEvent;
use Statix\Commands\ClearBuilds;
use Statix\Commands\MakeCommand;
use Statix\Commands\BuildCommand;
use Statix\Commands\MakeProvider;
use Statix\Commands\ServeCommand;
use Statix\Commands\WatchCommand;
use Statix\Commands\MakeComponent;
use Illuminate\Console\Application;
use Illuminate\Support\ServiceProvider;
use Statix\Commands\BuildHttpCommand;
use Statix\Commands\ClearCompiledViews;
use Statix\Events\CliCommandsRegistered;

class CliServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cli', function() {
            return new Application(
                $this->app, 
                $this->app->make('events'),
                $this->app->make('config')->get('site.version', ''),
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
        $cli = tap($this->app->make('cli'))
            ->setName($this->app->make('config')->get('site.name', 'Statix Application'));

        $cli->app = $cli->getLaravel();

        $this->app->make('statix')->cli = $cli;

        event(new CliBound($cli));
        
        $this->registerDefaultCommands($cli);

        event(new CliCommandsRegistered($cli));
    }

    private function registerDefaultCommands($cli): void
    {
        $cli->resolveCommands([
            BuildCommand::class,
            BuildHttpCommand::class,
            ClearBuilds::class,
            ClearCompiledViews::class,
            MakeCommand::class,
            MakeComponent::class,
            MakeEvent::class,
            MakeProvider::class,
            ServeCommand::class,
            WatchCommand::class,
        ]);
    }
}