<?php

namespace Statix\Providers;

use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\View\Factory as ViewFactoryContact;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('files', function() {
            return new Filesystem;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        File::ensureDirectoryExists($this->app->make('paths')->get('view_cache'));

        $viewResolver = new EngineResolver;

        $bladeCompiler = new BladeCompiler(
            $this->app->make('files'),
            $this->app->make('paths')->get('view_cache'),
        );

        $viewResolver->register('blade', function() use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewResolver->register('php', function() {
            return new PhpEngine($this->app->make('files'));
        });

        $viewFinder = new FileViewFinder(
            $this->app->make('files'), [
                $this->app->make('paths')->get('views'), 
                $this->app->make('paths')->get('content')
            ]
        );

        $viewFactory = tap(new ViewFactory(
            $viewResolver, 
            $viewFinder,
            $this->app->make('events'),
        ))->setContainer($this->app);

        $this->app->instance(ViewFactoryContact::class, $viewFactory);

        $this->app->alias(
            ViewFactoryContact::class, 
            (new class extends View {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor(),
        );

        $this->app->instance(BladeCompiler::class, $bladeCompiler);

        $this->app->alias(
            BladeCompiler::class, 
            (new class extends Blade {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor()
        );
    }
}