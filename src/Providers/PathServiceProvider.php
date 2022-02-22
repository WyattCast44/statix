<?php

namespace Statix\Providers;

use Exception;
use Statix\Events\PathsBound;
use Illuminate\Config\Repository;
use Statix\Events\PathsRegistered;
use Illuminate\Support\ServiceProvider;

class PathServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('paths', function() {
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
        $cwd = $this->app->basePath();

        $paths = tap($this->app->make('paths'), function($repo) use ($cwd) {
            $repo->set([
                'cwd' => $cwd,
                'app_path' => $cwd . '\app',
                'env_file' => $cwd . '\.env',
                'resource_path' => $cwd . '\resources',
                'builds' => $cwd . '\builds',
                'config' => $cwd . '\config',
                'content' => $cwd . '\resources\content',
                'database' => $cwd . '\database',
                'lang_path' => $cwd . '\lang',
                'log_path' => $cwd . '\storage\logs\statix.log',
                'public' => $cwd . '\public',
                'storage' => $cwd . '\storage',
                'routes' => $cwd . '\routes',
                'views' => $cwd . '\resources\views',
                'view_cache' => $cwd . '\storage\framework\views',
            ]);
        });

        $this->app['events']->dispatch(new PathsBound($paths));
    }
}