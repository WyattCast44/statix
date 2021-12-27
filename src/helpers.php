<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Statix\Application;

if(!function_exists('path')) {

    /**
     * Get the given named path
     * 
     * @return mixed
     */
    function path($key = null, $default = null): mixed
    {
        if($key != null) {
            return Container::getInstance()->make('paths')->get($key, $default);
        }

        return Container::getInstance()->make('paths');
    }

}

if(!function_exists('view')) {

    function view($template, $data = [])
    {
        return Container::getInstance()
            ->make(ViewFactory::class)
            ->make($template, $data)
            ->render();
    }

}

if(!function_exists('app')) {

    function app(): Application
    {
        return Container::getInstance()->make(Application::class);
    }

}

if(!function_exists('container')) {

    function container(): Container
    {
        return Container::getInstance();
    }

}