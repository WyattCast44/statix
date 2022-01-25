<?php

use Statix\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Statix\Support\Container;
use Statix\Routing\RouteRegistrar;
use Illuminate\Contracts\View\Factory as ViewFactory;

if (! function_exists('app')) {

    /**
     * Get the available statix/illuminate container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = []): mixed
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }

}

if (! function_exists('app_path')) {

    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = ''): string
    {
        return statix()->paths->append('app_path', $path === '' ? null : $path);
    }

}

if (! function_exists('base_path')) {

    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = ''): string
    {
        return app()->paths->append('cwd', $path);
    }

}

if(!function_exists('config')) {

    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */

    function config($key = null, $default = null): mixed
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }

}

if (! function_exists('config_path')) {

    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = ''): string
    {
        return app()->paths->append('config', $path);
    }

}

if(!function_exists('view')) {

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }

}

if(!function_exists('container')) {

    /**
     * Obtain the instance of the Statix/Illuminate container
     *
     * @return Statix\Support\Container
     */
    function container(): Container
    {
        return Container::getInstance();
    }

}

if(!function_exists('statix')) {
    
    /**
     * Obtain the instance of the Statix Application
     *
     * @return Statix\Application
     */
    function statix(): Application
    {
        return app()->make(Application::class);
    }

}

if(!function_exists('path')) {

    function path($key = null, $default = null): mixed
    {
        if($key != null) {
            return realpath(app()->make('paths')->get($key, $default));
        }

        return app()->make('paths');
    }

}

if(!function_exists('path_join')) {

    function path_join($key, ...$appends): mixed
    {
        return app()->make('paths')->get($key) . implode('', $appends);
    }

    function path_build($key, ...$appends): mixed
    {
        return rtrim(app()->make('paths')->get($key), '/') . '/' . implode('/', $appends);
    }

}

if(!function_exists('path_build')) {

    function path_build($key, ...$appends): mixed
    {
        return rtrim(app()->make('paths')->get($key), '/') . '/' . implode('/', $appends);
    }

}

if(!function_exists('route')) {

    function route($name, $props = null)
    {
        $registrar = app()->make(RouteRegistrar::class);

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


