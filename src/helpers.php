<?php

use Statix\Application;
use Statix\Support\Container;
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
            return container()->make('paths')->get($key, $default);
        }

        return container()->make('paths');
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