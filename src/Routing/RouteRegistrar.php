<?php 

namespace Statix\Routing;

use Statix\Routing\Route;

class RouteRegistrar
{
    protected $routes = [];

    public function add(Route $route)
    {
        $this->routes[$route->uri] = [
            'uri' => $route->uri,
            'name' => $route->name,
            'view' => $route->view,
            'data' => $route->data,
            'handler' => $route->handler,
            'sequence' => $route->sequence,
        ];

        return $this;
    }
}