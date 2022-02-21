<?php

namespace Statix;

use Illuminate\Support\Env;
use Illuminate\Support\Str;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use Statix\Events\PathsRegistered;
use Statix\Events\ProvidersBooted;
use Illuminate\Container\Container;
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
use Statix\Contracts\Application as ApplicationContract;
use Illuminate\Console\Application as ConsoleApplication;
use Statix\Application as StatixApplication;

class Application extends Container implements ApplicationContract
{
    const VERSION = '0.0.1';

    protected string $basePath;

    public Repository $paths;

    public Repository $config;

    public ConsoleApplication $cli;

    public Collection $defaultProviders;

    public Collection $providers;

    public static function new(string $path = null): static
    {   
        return new static($path);
    }

    public function __construct(string $basePath = null)
    {
        (new Collision)->register();
        
        $this->basePath = ($basePath != null) ? $basePath : getcwd();
        
        $this
            ->ensureContainerIsBinded()
            ->ensureDefaultServiceProvidersAreRegistered()
            ->ensureDefaultServiceProvidersAreBooted()
            ->ensureUserPathsAreRegistered()
            ->ensureUserServiceProvidersAreRegistered()
            ->ensureUserHelpersFileIsLoaded()
            ->ensureUserCommandsAreRegistered();
    }

    public function version(): string
    {
        return self::VERSION;
    }

    public function appPath(string $path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app'($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function basePath($path = ''): string
    {
        return $this->basePath.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function configPath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function databasePath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'database'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function langPath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'public'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function publicPath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'public'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function resourcePath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function storagePath(string $path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'storage'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }
    
    public function viewPath(string $path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources/views'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function environment(string|array ...$environments): string|bool
    {
        if (count($environments) > 0) {
            $patterns = is_array($environments[0]) ? $environments[0] : $environments;

            return Str::is($patterns, $this['env']);
        }

        return $this['env'];
    }

    public function runningInConsole(): bool
    {
        if ($this->isRunningInConsole === null) {
            $this->isRunningInConsole = Env::get('APP_RUNNING_IN_CONSOLE') ?? (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg');
        }

        return $this->isRunningInConsole;
    }

    public function runningUnitTests(): bool
    {
        return $this->bound('env') && $this['env'] === 'testing';
    }

    public function getLocale(): string
    {
        return $this['config']->get('site.locale');
    }

    public function setLocale($locale): self
    {
        $this['config']->set('site.locale', $locale);

        // $this['events']->dispatch(new LocaleUpdated($locale));

        return $this;
    }

    public function isLocal(): bool
    {
        return $this['env'] === 'local';
    }

    public function isProduction(): bool
    {
        return $this['env'] === 'production';
    }

    private function ensureContainerIsBinded()
    {
        self::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
        $this->instance(ApplicationContract::class, $this);
        Facade::setFacadeApplication($this);
        
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
            
            $obj = new $provider($this);

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
            $obj = new $provider($this);

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

    public function setCli(ConsoleApplication $cli): self
    {
        $this->cli = $cli;

        return $this;
    }
}