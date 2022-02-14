<?php

namespace Statix\Providers;

use Illuminate\Support\Collection;
use Statix\Events\ProvidersBooted;
use Statix\Events\ProvidersRegistered;
use Illuminate\Support\ServiceProvider;

class UserServiceProvidersProvider extends ServiceProvider
{
    protected Collection $providers;
    
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
        $this
            ->discoverProvidersInAppNamespace()
            ->discoveredConfigRegisteredProviders()
            ->ensureProvidersRegisteredAndBooted();
    }

    protected function discoverProvidersInAppNamespace()
    {
        if(!$this->app->make('config')->get('site.autodiscover_providers', false)) {
            return $this;
        }

        $path = $this->app->make('paths')->get('app_path') . '/Providers';

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

        return $this;
    }

    protected function discoveredConfigRegisteredProviders()
    {
        if($this->app->make('config')->has('site.providers')) {
            $this->providers = $this->providers->concat($this->app->make('config')->get('site.providers', []));
        }

        return $this;
    }

    protected function ensureProvidersRegisteredAndBooted()
    {
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
}