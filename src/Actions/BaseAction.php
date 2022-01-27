<?php 

namespace Statix\Actions;

use Statix\Support\Container;

abstract class BaseAction
{
    public Container $app;

    public function __construct(Container $app) 
    {
        $this->app = $app->getInstance();
    }

    public function execute(): void {}
}