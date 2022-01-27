<?php

namespace Statix;

use Exception;
use Dotenv\Dotenv;
use Statix\Support\Container;
use Illuminate\Console\Command;
use Statix\Commands\ClearBuilds;
use Statix\Commands\MakeCommand;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Statix\Commands\BuildCommand;
use Statix\Commands\MakeProvider;
use Statix\Commands\ServeCommand;
use Statix\Commands\WatchCommand;
use Statix\Commands\MakeComponent;
use Illuminate\Support\Collection;
use Statix\Routing\RouteRegistrar;
use Statix\Support\PathRepository;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Engines\PhpEngine;
use Statix\Commands\ClearCompiledViews;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\Compilers\BladeCompiler;
use NunoMaduro\Collision\Provider as Collision;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Contracts\View\Factory as ViewFactoryContact;
use Illuminate\Contracts\Foundation\Application as FoundationApplication;

class Application
{
    public Container $container;

    public Repository $paths;

    public Repository $config;

    public ConsoleApplication $cli;

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
            ->ensurePathsAreBindedAndConfigured()
            ->ensureEnvFilesAreLoaded()
            ->ensureConfigIsBindedAndLoaded()
            ->ensureCliApplicationIsBinded()
            ->ensureServiceProvidersAreRegistered()
            ->ensureServiceProvidersAreBooted()
            ->ensureDefaultCommandsAreRegistered()
            ->ensureUserCommandsAreRegistered()
            ->ensureUserPathsAreRegistered()
            ->ensureBladeEngineIsConfigured()
            ->ensureRequiredPathsExist()
            ->ensureRouteRegistrarIsBinded();
    }

    private function ensureContainerIsBinded()
    {
        $this->container = tap(new Container, function(Container $container) {
            $container->setInstance($container);
            $container->instance(Application::class, $this);
            $container->instance(FoundationApplication::class, $container);
        });

        Facade::setFacadeApplication($this->container);

        return $this;
    }

    private function ensurePathsAreBindedAndConfigured()
    {
        $cwd = getcwd();

        $this->container->singleton('paths', function() {
            return new Repository;
        });

        // this should be cacheable ... ?

        $this->paths = tap($this->container->make('paths'), function($repo) use ($cwd) {
            $repo->set([
                'cwd' => $cwd,
                'app_path' => $cwd . '/app',
                'env_file' => $cwd . '/.env',
                'resource_path' => $cwd . '/resources',
                'assets' => $cwd . '/resources/assets',
                'builds' => $cwd . '/builds',
                'config' => $cwd . '/config',
                'content' => $cwd . '/resources/content',
                'public' => $cwd . '/public',
                'routes' => $cwd . '/routes',
                'views' => $cwd . '/resources/views',
                'view_cache' => $cwd . '/builds/_cache/views',
            ]);
        });

        return $this;
    }

    private function ensureEnvFilesAreLoaded()
    {
        if(file_exists($this->paths->get('env_file'))) {
            (Dotenv::createImmutable($this->paths->get('cwd')))->safeLoad();
        }

        return $this;
    }

    private function ensureConfigIsBindedAndLoaded()
    {
        $this->container->singleton('config', function() {
            return new Repository;
        });

        $this->config = $this->container->make('config');

        $this->reloadConfigFiles();

        return $this;
    }

    public function reloadConfigFiles()
    {
        $path = $this->paths->get('config');

        // this should be cacheable ... ?

        $items = collect(scandir($path))
            ->reject(function ($file) {
                return is_dir($file);
            })->reject(function ($file) {
                return (pathinfo($file)['extension'] != 'php');
            })->mapWithKeys(function ($file) use ($path) {
                return [basename($file, '.php') => require $path . '/' . $file];
            })->toArray();

        $this->config->set($items);

        return $this;
    }

    private function ensureCliApplicationIsBinded()
    {
        $this->container->singleton('cli', function() {
            return new ConsoleApplication(
                $this->container, 
                new Dispatcher($this->container),
                $this->config->get('app.version', ''),
            );
        });

        $this->cli = tap($this->container->make('cli'))
            ->setName($this->config->get('app.name', 'Statix Application'));
        
        Command::macro('app', function() {
            return $this->laravel;
        });

        Command::macro('container', function() {
            return $this->laravel;
        });

        return $this;
    }

    public function ensureServiceProvidersAreRegistered()
    {
        $path = $this->paths->get('app_path') . '/Providers';

        if(!is_dir($path)) {
            $this->providers = collect();

            return $this;
        }

        // this should be cacheable ... ?

        $items = collect(scandir($path))
            ->reject(function ($file) {
                return is_dir($file);
            })->reject(function ($file) {
                return (pathinfo($file)['extension'] != 'php');
            })->map(function($file) {
                $class = "App\\Providers\\" . basename($file, '.php');
                
                $obj = new $class($this->container);

                if(method_exists($obj, 'register')) {
                    $obj->register();
                }

                return $obj;
            });

        $this->providers = $items;

        return $this;
    }

    public function ensureServiceProvidersAreBooted()
    {
        $this->providers->each(function ($provider) {
            if(method_exists($provider, 'boot')) {
                $provider->boot();
            }
        });

        return $this;
    }

    private function ensureDefaultCommandsAreRegistered()
    {
        $this->cli->resolveCommands([
            BuildCommand::class,
            ClearBuilds::class,
            ClearCompiledViews::class,
            MakeCommand::class,
            MakeComponent::class,
            MakeProvider::class,
            ServeCommand::class,
            WatchCommand::class,
        ]);

        return $this;
    }

    private function ensureUserCommandsAreRegistered()
    {
        if($this->config->has('app.commands')) {
            $this->cli->resolveCommands($this->config->get('app.commands', []));
        }

        return $this;
    }

    private function ensureUserPathsAreRegistered()
    {
        if($this->config->has('app.paths')) {
            collect($this->config->get('app.paths', []))->each(function($path, $key) {
                $this->paths->set($key, $path, true);
            });
        }

        return $this;
    }

    private function ensureRequiredPathsExist()
    {
        collect(['assets', 'builds', 'content', 'routes', 'views'])->each(function($path) {
            if(!is_dir($this->paths->get($path))) {
                if(!file_exists($this->paths->get($path))) {
                    throw new Exception("The '$path' path must be defined and exist. Current set to: " . $this->paths->get($path));
                }
            }
        });

        return $this;
    }

    private function ensureBladeEngineIsConfigured()
    {
        $this->container->bind('files', function() {
            return new Filesystem;
        });
                
        File::ensureDirectoryExists($this->paths->get('view_cache'));

        $viewResolver = new EngineResolver;

        $bladeCompiler = new BladeCompiler(
            $this->container->make('files'),
            $this->paths->get('view_cache'),
        );

        $viewResolver->register('blade', function() use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewResolver->register('php', function() {
            return new PhpEngine($this->container->make('files'));
        });

        $viewFinder = new FileViewFinder(
            $this->container->make('files'), [
                $this->paths->get('views'), 
                $this->paths->get('content')
            ]
        );

        $viewFactory = tap(new ViewFactory(
            $viewResolver, 
            $viewFinder,
            new Dispatcher($this->container),
        ))->setContainer($this->container);

        $this->container->instance(ViewFactoryContact::class, $viewFactory);

        $this->container->alias(
            ViewFactoryContact::class, 
            (new class extends View {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor(),
        );

        $this->container->instance(BladeCompiler::class, $bladeCompiler);

        $this->container->alias(
            BladeCompiler::class, 
            (new class extends Blade {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor()
        );

        return $this;
    }

    private function ensureRouteRegistrarIsBinded()
    {
        $this->container->singleton(RouteRegistrar::class, function() {
            return new RouteRegistrar;
        });
        
        return $this;
    }
}