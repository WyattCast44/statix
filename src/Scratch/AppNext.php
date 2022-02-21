<?php

namespace Statix;

use RuntimeException;
use Illuminate\Support\Env;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Statix\Exceptions\NotImplementedException;

class AppNext extends Container implements Application
{
    const VERSION = '0.0.1';

    protected string $basePath;

    /**
     * Indicates if the application has been bootstrapped before.
     */
    protected bool $hasBeenBootstrapped = false;

    /**
     * Indicates if the application has "booted".
     */
    protected bool $booted = false;

    /**
     * The array of booting callbacks.
     *
     * @var callable[]
     */
    protected $bootingCallbacks = [];

    /**
     * The array of booted callbacks.
     *
     * @var callable[]
     */
    protected $bootedCallbacks = [];

    /**
     * The array of terminating callbacks.
     *
     * @var callable[]
     */
    protected $terminatingCallbacks = [];

    /**
     * All of the registered service providers.
     *
     * @var \Illuminate\Support\ServiceProvider[]
     */
    protected $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The deferred services and their providers.
     *
     * @var array
     */
    protected $deferredServices = [];

    /**
     * The custom application path defined by the developer.
     *
     * @var string
     */
    protected $appPath;

    /**
     * The custom database path defined by the developer.
     *
     * @var string
     */
    protected $databasePath;

    /**
     * The custom language file path defined by the developer.
     *
     * @var string
     */
    protected $langPath;

    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    protected $environmentPath;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * Indicates if the application is running in the console.
     *
     * @var bool|null
     */
    protected $isRunningInConsole;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The prefixes of absolute cache paths for use during normalization.
     *
     * @var string[]
     */
    protected $absoluteCachePathPrefixes = ['/', '\\'];

    public static function new(string $path = null)
    {
        return new self($path);
    }

    public function __construct(string $path = null)
    {
        $this->basePath = ($path != null) ? $path : getcwd();
    }

    public function version(): string
    {
        return self::VERSION;
    }

    /**
     * Get the base path of the installation.
     *
     * @param  string  $path
     */
    public function basePath($path = ''): string
    {
        return $this->basePath.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @param  string  $path
     */
    public function bootstrapPath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'storage/framework/bootstrap'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     */
    public function configPath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the database directory.
     *
     * @param  string  $path
     */
    public function databasePath($path = ''): string
    {
        return ($this->databasePath ?: $this->basePath.DIRECTORY_SEPARATOR.'database').($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     */
    public function resourcePath($path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the storage directory.
     *
     * @param  string  $path
     * @return string
     */
    public function storagePath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'storage'.($path != '' ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get or check the current application environment.
     *
     * @param  string|array  $environments
     * @return string|bool
     */
    public function environment(...$environments)
    {
        if (count($environments) > 0) {
            $patterns = is_array($environments[0]) ? $environments[0] : $environments;

            return Str::is($patterns, $this['env']);
        }

        return $this['env'];
    }

    /**
     * Determine if the application is running in the console.
     */
    public function runningInConsole(): bool
    {
        if ($this->isRunningInConsole === null) {
            $this->isRunningInConsole = Env::get('APP_RUNNING_IN_CONSOLE') ?? (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg');
        }

        return $this->isRunningInConsole;
    }

    /**
     * Determine if the application is running unit tests.
     */
    public function runningUnitTests(): bool
    {
        return $this->bound('env') && $this['env'] === 'testing';
    }

    /**
     * Get an instance of the maintenance mode manager implementation.
     *
     * @return \Statix\Exceptions\NotImplementedException
     */
    public function maintenanceMode()
    {
        new NotImplementedException('This feature is not implemented in statix.');
    }

    public function isDownForMaintenance(): bool
    {
        return false;
    }

    public function isBooted(): bool
    {
        return (bool) $this->booted;
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders(): void
    {
        $providers = Collection::make($this->make('config')->get('app.providers'))
                        ->partition(function ($provider) {
                            return str_starts_with($provider, 'Illuminate\\');
                        });

        $providers->splice(1, 0, [$this->make(PackageManifest::class)->providers()]);

        // (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))
        //             ->load($providers->collapse()->toArray());
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  bool  $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $force = false)
    {
        if (($registered = $this->getProvider($provider)) && ! $force) {
            return $registered;
        }

        // If the given "provider" is a string, we will resolve it, passing in the
        // application instance automatically for the developer. This is simply
        // a more convenient way of specifying your service provider classes.
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        $provider->register();

        // If there are bindings / singletons set as properties on the provider we
        // will spin through them and register them with the application, which
        // serves as a convenience layer while registering a lot of bindings.
        if (property_exists($provider, 'bindings')) {
            foreach ($provider->bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }

        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $this->singleton($key, $value);
            }
        }

        $this->markAsRegistered($provider);

        // If the application has already booted, we will call this boot method on
        // the provider class so it has an opportunity to do its boot logic and
        // will be ready for any usage by this developer's application logic.
        if ($this->isBooted()) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        return array_values($this->getProviders($provider))[0] ?? null;
    }

    /**
     * Mark the given provider as registered.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * Register a deferred provider and service.
     *
     * @param  string  $provider
     * @param  string|null  $service
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null);

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param  string  $provider
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProvider($provider);

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot();

    /**
     * Register a new boot listener.
     *
     * @param  callable  $callback
     * @return void
     */
    public function booting($callback);

    /**
     * Register a new "booted" listener.
     *
     * @param  callable  $callback
     * @return void
     */
    public function booted($callback);

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  array  $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers);

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Get the registered service provider instances if any exist.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return array
     */
    public function getProviders($provider);

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped();

    /**
     * Load and boot all of the remaining deferred providers.
     *
     * @return void
     */
    public function loadDeferredProviders();

    /**
     * Set the current application locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale($locale);

    /**
     * Determine if middleware has been disabled for the application.
     *
     * @return bool
     */
    public function shouldSkipMiddleware();

    /**
     * Register a terminating callback with the application.
     *
     * @param  callable|string  $callback
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function terminating($callback);

    /**
     * Terminate the application.
     *
     * @return void
     */
    public function terminate();

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        foreach ([
            'app' => [
                self::class, 
                \Illuminate\Contracts\Container\Container::class, 
                \Illuminate\Contracts\Foundation\Application::class, 
                // \Psr\Container\ContainerInterface::class,
            ],
            'blade.compiler' => [
                \Illuminate\View\Compilers\BladeCompiler::class
            ],
            // 'cache' => [
            //     \Illuminate\Cache\CacheManager::class, 
            //     \Illuminate\Contracts\Cache\Factory::class
            // ],
            // 'cache.store' => [
            //     \Illuminate\Cache\Repository::class, 
            //     \Illuminate\Contracts\Cache\Repository::class, 
            //     \Psr\SimpleCache\CacheInterface::class
            // ],
            // 'cache.psr6' => [
            //     \Symfony\Component\Cache\Adapter\Psr16Adapter::class, 
            //     \Symfony\Component\Cache\Adapter\AdapterInterface::class, \Psr\Cache\CacheItemPoolInterface::class
            // ],
            'config' => [
                \Illuminate\Config\Repository::class, 
                \Illuminate\Contracts\Config\Repository::class
            ],
            // 'cookie' => [
            //     \Illuminate\Cookie\CookieJar::class, 
            //     \Illuminate\Contracts\Cookie\Factory::class, 
            //     \Illuminate\Contracts\Cookie\QueueingFactory::class
            // ],
            'db' => [
                \Illuminate\Database\DatabaseManager::class, 
                \Illuminate\Database\ConnectionResolverInterface::class
            ],
            'db.connection' => [
                \Illuminate\Database\Connection::class, 
                \Illuminate\Database\ConnectionInterface::class
            ],
            'db.schema' => [
                \Illuminate\Database\Schema\Builder::class
            ],
            'encrypter' => [
                \Illuminate\Encryption\Encrypter::class, 
                \Illuminate\Contracts\Encryption\Encrypter::class, 
                \Illuminate\Contracts\Encryption\StringEncrypter::class
            ],
            'events' => [
                \Illuminate\Events\Dispatcher::class, 
                \Illuminate\Contracts\Events\Dispatcher::class
            ],
            'files' => [
                \Illuminate\Filesystem\Filesystem::class
            ],
            'filesystem' => [
                \Illuminate\Filesystem\FilesystemManager::class, 
                \Illuminate\Contracts\Filesystem\Factory::class
            ],
            'filesystem.disk' => [\Illuminate\Contracts\Filesystem\Filesystem::class],
            'filesystem.cloud' => [\Illuminate\Contracts\Filesystem\Cloud::class],
            'hash' => [\Illuminate\Hashing\HashManager::class],
            'hash.driver' => [\Illuminate\Contracts\Hashing\Hasher::class],
            'translator' => [\Illuminate\Translation\Translator::class, \Illuminate\Contracts\Translation\Translator::class],
            'log' => [\Illuminate\Log\LogManager::class, \Psr\Log\LoggerInterface::class],
            // 'mail.manager' => [\Illuminate\Mail\MailManager::class, \Illuminate\Contracts\Mail\Factory::class],
            // 'mailer' => [\Illuminate\Mail\Mailer::class, \Illuminate\Contracts\Mail\Mailer::class, \Illuminate\Contracts\Mail\MailQueue::class],
            // 'auth.password' => [\Illuminate\Auth\Passwords\PasswordBrokerManager::class, \Illuminate\Contracts\Auth\PasswordBrokerFactory::class],
            // 'auth.password.broker' => [\Illuminate\Auth\Passwords\PasswordBroker::class, \Illuminate\Contracts\Auth\PasswordBroker::class],
            // 'queue' => [\Illuminate\Queue\QueueManager::class, \Illuminate\Contracts\Queue\Factory::class, \Illuminate\Contracts\Queue\Monitor::class],
            // 'queue.connection' => [\Illuminate\Contracts\Queue\Queue::class],
            // 'queue.failer' => [\Illuminate\Queue\Failed\FailedJobProviderInterface::class],
            'redirect' => [\Illuminate\Routing\Redirector::class],
            // 'redis' => [\Illuminate\Redis\RedisManager::class, \Illuminate\Contracts\Redis\Factory::class],
            // 'redis.connection' => [\Illuminate\Redis\Connections\Connection::class, \Illuminate\Contracts\Redis\Connection::class],
            'request' => [\Illuminate\Http\Request::class, \Symfony\Component\HttpFoundation\Request::class],
            'router' => [\Illuminate\Routing\Router::class, \Illuminate\Contracts\Routing\Registrar::class, \Illuminate\Contracts\Routing\BindingRegistrar::class],
            // 'session' => [\Illuminate\Session\SessionManager::class],
            // 'session.store' => [\Illuminate\Session\Store::class, \Illuminate\Contracts\Session\Session::class],
            'url' => [\Illuminate\Routing\UrlGenerator::class, \Illuminate\Contracts\Routing\UrlGenerator::class],
            'validator' => [\Illuminate\Validation\Factory::class, \Illuminate\Contracts\Validation\Factory::class],
            'view' => [\Illuminate\View\Factory::class, \Illuminate\Contracts\View\Factory::class],
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        $this->buildStack = [];
        $this->loadedProviders = [];
        $this->bootedCallbacks = [];
        $this->bootingCallbacks = [];
        $this->deferredServices = [];
        $this->reboundCallbacks = [];
        $this->serviceProviders = [];
        $this->resolvingCallbacks = [];
        $this->terminatingCallbacks = [];
        $this->beforeResolvingCallbacks = [];
        $this->afterResolvingCallbacks = [];
        $this->globalBeforeResolvingCallbacks = [];
        $this->globalResolvingCallbacks = [];
        $this->globalAfterResolvingCallbacks = [];
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath('composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->path()) === realpath($this->basePath($pathChoice))) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }
}