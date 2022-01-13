<?php

use Statix\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Statix\Support\Container;
use Statix\Routing\RouteRegistrar;
use Illuminate\Contracts\View\Factory as ViewFactory;

if(!function_exists('container')) {

    function container(): Container
    {
        return Container::getInstance();
    }

}

if(!function_exists('app')) {

    function app(): Application
    {
        return container()->make(Application::class);
    }

}

if(!function_exists('config')) {

    function config($key = null, $default = null): mixed
    {
        if($key != null) {
            return container()->make('config')->get($key, $default);
        }

        return container()->make('config');
    }

}

if(!function_exists('path')) {

    function path($key = null, $default = null): mixed
    {
        if($key != null) {
            return realpath(container()->make('paths')->get($key, $default));
        }

        return container()->make('paths');
    }

}

if(!function_exists('path_join')) {

    function path_join($key, ...$appends): mixed
    {
        return container()->make('paths')->get($key) . implode('', $appends);
    }

    function path_build($key, ...$appends): mixed
    {
        return rtrim(container()->make('paths')->get($key), '/') . '/' . implode('/', $appends);
    }

}

if(!function_exists('path_build')) {

    function path_build($key, ...$appends): mixed
    {
        return rtrim(container()->make('paths')->get($key), '/') . '/' . implode('/', $appends);
    }

}

if(!function_exists('app_path')) {

    function app_path(...$appends): mixed
    {
        return container()->make('paths')->get('app_path') . implode('', $appends);
    }

}

if(!function_exists('view')) {

    function view($template, $data = [])
    {
        return container()
            ->make(ViewFactory::class)
            ->make($template, $data)
            ->render();
    }

}

if(!function_exists('route')) {

    function route($name, $props = null)
    {
        $registrar = container()->make(RouteRegistrar::class);

        if(Arr::has($registrar->namedRoutes, $name)) {
            
            $path = $registrar->namedRoutes[$name];

            if(Str::containsAll($path, ['{', '}'])) {

                dd('need to sub props');

            } else {

                return $path;

            }

        } else {

            throw new Exception('Unknown route: ' . $name);

        }
    }

}

if(!function_exists('asset')) {

    function asset($path)
    {
        return config('app.url', 'http://localhost:8080') . '/' . $path;
    }

}

