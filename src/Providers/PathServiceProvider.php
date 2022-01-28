<?php

namespace Statix\Providers;

use Statix\Events\PathsBound;
use Illuminate\Config\Repository;
use Statix\Actions\LoadConfigFiles;
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
        $cwd = getcwd();

        $paths = tap($this->app->make('paths'), function($repo) use ($cwd) {
            $repo->set([
                'cwd' => $cwd,
                'app_path' => $cwd . '\app',
                'env_file' => $cwd . '\.env',
                'resource_path' => $cwd . '\resources',
                'builds' => $cwd . '\builds',
                'config' => $cwd . '\config',
                'content' => $cwd . '\resources\content',
                'public' => $cwd . '\public',
                'routes' => $cwd . '\routes',
                'views' => $cwd . '\resources\views',
                'view_cache' => $cwd . '\storage\framework\views',
            ]);
        });

        $this->app->make('statix')->paths = $paths;

        event(new PathsBound($paths));
    }
}