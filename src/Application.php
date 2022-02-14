<?php

namespace Statix;

use Statix\Support\Container;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use Statix\Events\PathsRegistered;
use Statix\Events\ProvidersBooted;
use Statix\Events\HelpersFileLoaded;
use Illuminate\Support\Facades\Facade;
use Statix\Events\ProvidersRegistered;
use Statix\Events\CliCommandsRegistered;
use Statix\Providers\CliServiceProvider;
use Statix\Events\DefaultProvidersBooted;
use Statix\Providers\PathServiceProvider;
use Statix\Providers\ViewServiceProvider;
use Statix\Providers\EventServiceProvider;
use Statix\Providers\RouteServiceProvider;
use Statix\Providers\ConfigServiceProvider;
use Statix\Providers\EnvFileServiceProvider;
use Statix\Events\DefaultProvidersRegistered;
use NunoMaduro\Collision\Provider as Collision;
use Statix\Providers\UserServiceProvidersProvider;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Contracts\Foundation\Application as FoundationApplication;

class Application
{
    public Container $container;

    public Repository $paths;

    public Repository $config;

    public ConsoleApplication $cli;

    public Collection $defaultProviders;

    public Collection $providers;

    public static function new(): static
    {   
        return new static;
    }

    public function __construct()
    {
        (new Collision)->register();

        $this
            ->ensureContainerIsBinded()
            ->ensureDefaultServiceProvidersAreRegistered()
            ->ensureDefaultServiceProvidersAreBooted()
            ->ensureUserPathsAreRegistered()
            ->ensureUserServiceProvidersAreRegistered()
            ->ensureUserHelpersFileIsLoaded()
            ->ensureUserCommandsAreRegistered();
    }

    private function ensureContainerIsBinded()
    {
        $this->container = tap(new Container, function(Container $container) {
            $container->setInstance($container);
            $container->instance(Application::class, $this);
            $container->alias(Application::class, 'statix');
            $container->instance(FoundationApplication::class, $container);
        });

        Facade::setFacadeApplication($this->container);

        return $this;
    }

    private function ensureDefaultServiceProvidersAreRegistered()
    {
        $this->defaultProviders = collect([
            EventServiceProvider::class,
            EnvFileServiceProvider::class,
            PathServiceProvider::class,
            ConfigServiceProvider::class,
            CliServiceProvider::class,
            ViewServiceProvider::class,
            RouteServiceProvider::class
        ])->map(function($provider) {
            
            $obj = new $provider($this->container);

            if(method_exists($obj, 'register')) {
                $obj->register();
            }

            return $obj;
        });

        event(new DefaultProvidersRegistered($this->defaultProviders));

        return $this;
    }

    private function ensureDefaultServiceProvidersAreBooted()
    {
        $this->defaultProviders->each(function ($provider) {
            if(method_exists($provider, 'boot')) {
                $provider->boot();
            }
        });

        event(new DefaultProvidersBooted($this->defaultProviders));

        return $this;
    }

    private function ensureUserPathsAreRegistered()
    {   
        if($this->config->has('site.paths')) {
            collect($this->config->get('site.paths', []))->each(function($path, $key) {
                $this->paths->set($key, $path, true);
            });

            event(new PathsRegistered($this->paths));
        }

        return $this;
    }

    private function ensureUserServiceProvidersAreRegistered()
    {
        $path = $this->paths->get('app_path') . '/Providers';

        if(!is_dir($path)) {

            $this->providers = collect();

        } else {

            $this->providers = collect(scandir($path))
                ->reject(function ($file) {
                    return is_dir($file);
                })->reject(function ($file) {
                    return (pathinfo($file)['extension'] != 'php');
                })->map(function($file) {
                    return "App\\Providers\\" . basename($file, '.php');
                });

        }

        if($this->config->has('site.providers')) {
            $this->providers = $this->providers->concat($this->config->get('site.providers', []));
        }

        $this->providers = $this->providers->map(function($provider) {
            $obj = new $provider($this->app);

            if(method_exists($obj, 'register')) {
                $obj->register();
            }

            return $obj;
        });

        event(new ProvidersRegistered($this->providers));

        $this->providers = $this->providers->map(function($provider) {
            if(method_exists($provider, 'boot')) {
                $provider->boot();
            }

            return $provider;
        });

        event(new ProvidersBooted($this->providers));

        return $this;
    }

    private function ensureUserHelpersFileIsLoaded()
    {
        if(file_exists($path = $this->paths->get('app_path') . '/helpers.php')) {
            require_once $path;

            event(new HelpersFileLoaded);
        }
        
        return $this;
    }

    private function ensureUserCommandsAreRegistered()
    {
        if($this->config->has('site.commands')) {
            $this->cli->resolveCommands($this->config->get('site.commands', []));
        }

        if($this->config->get('site.autodiscover_commands', false)) {

            $path = $this->paths->get('app_path') . '/Console/Commands';
            
            if(file_exists($path)) {            

                $items = collect(scandir($path))
                    ->reject(function ($file) {
                        return is_dir($file);
                    })->reject(function ($file) {
                        return (pathinfo($file)['extension'] != 'php');
                    })->map(function ($file) {
                        return "App\\Console\\Commands\\" . basename($file, '.php');
                    });
    
                $this->cli->resolveCommands($items->toArray());
            }
        }

        event(new CliCommandsRegistered($this->cli));

        return $this;
    }
}