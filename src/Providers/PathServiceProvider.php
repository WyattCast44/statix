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
                'storage' => $cwd . '\storage',
                'routes' => $cwd . '\routes',
                'views' => $cwd . '\resources\views',
                'view_cache' => $cwd . '\storage\framework\views',
            ]);
        });

        $this->app->paths = $paths;

        $base = $this->app->basePath();

        $this->app->bind('path.base', $base);
        $this->app->bind('path', $base . DIRECTORY_SEPARATOR . 'app');
        $this->app->bind('path.config', $base . DIRECTORY_SEPARATOR . 'config');
        $this->app->bind('path.public', $base . DIRECTORY_SEPARATOR . 'public');
        $this->app->bind('path.storage', $base . DIRECTORY_SEPARATOR . 'storage');
        $this->app->bind('path.database', $base . DIRECTORY_SEPARATOR . 'database');
        $this->app->bind('path.resources', $base . DIRECTORY_SEPARATOR . 'resources');
        $this->app->bind('path.bootstrap', $base . DIRECTORY_SEPARATOR . 'storage/framework/bootstrap');
        $this->app->bind('path.lang', $base . DIRECTORY_SEPARATOR . 'lang');

        event(new PathsBound($paths));

        $this->ensureRequiredPathsExist($paths);
    }

    private function ensureRequiredPathsExist($paths)
    {
        collect(['routes', 'views', 'public'])->each(function($path) use ($paths) {
            if(!is_dir($paths->get($path))) {
                if(!file_exists($paths->get($path))) {
                    throw new Exception("The '$path' path must be defined and exist. Currently set to: " . $paths->get($path));
                }
            }
        });

        return $this;
    }
}