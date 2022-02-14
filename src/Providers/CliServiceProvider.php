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
use Statix\Commands\MakeEventsFile;
use Illuminate\Console\Application;
use Statix\Commands\MakeHelpersFile;
use Statix\Commands\BuildHttpCommand;
use Illuminate\Support\ServiceProvider;
use Statix\Commands\ClearCompiledViews;
use Statix\Events\DefaultCliCommandsRegistered;

class CliServiceProvider extends ServiceProvider
{
    protected $defaultCommands = [
        BuildCommand::class,
        BuildHttpCommand::class,
        ClearBuilds::class,
        ClearCompiledViews::class,
        MakeCommand::class,
        MakeComponent::class,
        MakeEvent::class,
        MakeEventsFile::class,
        MakeHelpersFile::class,
        MakeProvider::class,
        ServeCommand::class,
        WatchCommand::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cli', function() {
            return tap(new Application(
                $this->app, 
                $this->app->make('events'),
                $this->app->make('config')->get('site.version', ''),
            ))->setName($this->app->make('config')->get('site.name'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $cli = $this->app->make('cli');

        $cli->app = $cli->getLaravel();

        $this->app->make('statix')->cli = $cli;

        event(new CliBound($cli));
        
        $cli->resolveCommands($this->defaultCommands);

        event(new DefaultCliCommandsRegistered($cli));
    }
}