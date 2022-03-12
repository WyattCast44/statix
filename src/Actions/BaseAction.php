<?php 

namespace Statix\Actions;

use Illuminate\Container\Container;

abstract class BaseAction
{
    public Container $app;

    public function __construct(Container $app) 
    {
        $this->app = $app->getInstance();
    }
}