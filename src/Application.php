<?php

namespace Statix;

use Exception;
use Dotenv\Dotenv;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Statix\Support\PathRepository;
use Statix\Routing\RouteRegistrar;
use Illuminate\View\FileViewFinder;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Facade;
use Statix\Commands\ClearCompiledViews;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Contracts\View\Factory as ViewFactoryContact;

class Application
{
    public Container $container;

    public PathRepository $paths;

    public Repository $config;

    public ConsoleApplication $cli;

    public function __construct()
    {
        $this
            ->ensureContainerIsBinded()
            ->ensurePathsAreBindedAndConfigured()
            ->ensureEnvFilesAreLoaded()
            ->ensureConfigIsBindedAndLoaded()
            ->ensureCliApplicationIsBinded()
            ->ensureDefaultCommandsAreRegistered()
            ->ensureUserCommandsAreRegistered()
            ->ensureUserPathsAreRegistered()
            ->ensureRequiredPathsExist()
            ->ensureBladeEngineIsConfigured()
            ->ensureRouteRegistrarIsBinded();
    }

    public static function new(): static
    {   
        return new static;
    }

    private function ensureContainerIsBinded()
    {
        $this->container = new Container;

        $this->container->instance(Application::class, $this);

        $this->container->setInstance($this->container);

        Facade::setFacadeApplication($this->container);

        return $this;
    }

    private function ensurePathsAreBindedAndConfigured()
    {
        $this->container->singleton('paths', function() {
            return new PathRepository;
        });

        $this->paths = tap($this->container->make('paths'), function($repo) {
            $cwd = getcwd();
            $repo->set('cwd', $cwd);
            $repo->set('env_file', $cwd . '/.env');
            $repo->set('assets', $cwd . '/resources/assets');
            $repo->set('builds', $cwd . '/builds');
            $repo->set('config', $cwd . '/config');
            $repo->set('content', $cwd . '/resources/content');
            $repo->set('views', $cwd . '/resources/views');
            $repo->set('view_cache', $cwd . '/builds/_cache/views');
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
            
            $path = $this->paths->get('config');

            $items = collect(scandir($path))
                ->reject(function ($file) {
                    return is_dir($file);
                })->reject(function ($file) {
                    return (pathinfo($file)['extension'] != 'php');
                })->mapWithKeys(function ($file) use ($path) {
                    return [basename($file, '.php') => require $path . '/' . $file];
                })->toArray();

            return new Repository($items);

        });

        $this->config = $this->container->make('config');

        return $this;
    }

    private function ensureCliApplicationIsBinded()
    {
        $this->container->singleton('cli', function() {
            return new ConsoleApplication(
                $this->container, 
                new Dispatcher($this->container),
                $this->config->get('app.version', '1.0.0'),
            );
        });

        Command::macro('container', function() {
            return $this->laravel;
        });

        $this->cli = $this->container->make('cli');

        $this->cli->setName($this->config->get('app.name'));

        return $this;
    }

    private function ensureDefaultCommandsAreRegistered()
    {
        $this->cli->resolveCommands([
            ClearCompiledViews::class
        ]);

        return $this;
    }

    private function ensureUserCommandsAreRegistered()
    {
        if($this->config->has('app.commands')) {
            collect($this->config->get('app.commands', []))->each(function($command) {
                $this->cli->resolve($command);
            });
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
        collect(['assets', 'builds', 'content', 'views', 'view_cache'])->each(function($path) {
            if(!is_dir($this->paths->get($path))) {
                throw new Exception("The '$path' path must be defined and exist. Current set to: " . $this->paths->get($path));
            }
        });

        return $this;
    }

    private function ensureBladeEngineIsConfigured()
    {
        $this->container->bind('fs', function() {
            return new Filesystem;
        });

        $viewResolver = new EngineResolver;

        $bladeCompiler = new BladeCompiler(
            $this->container->make('fs'),
            $this->paths->get('view_cache'),
        );

        $viewResolver->register('blade', function() use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewFinder = new FileViewFinder(
            $this->container->make('fs'),
            [$this->paths->get('views')]
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

        require path('cwd'). '/routes/web.php';

        dd($this->container->make(RouteRegistrar::class));

        return $this;
    }
}