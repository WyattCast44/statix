<?php 

namespace Statix\Routing;

use Statix\Routing\Route;

class RouteRegistrar
{
    public $routes = [];

    public $namedRoutes = [];

    public function add(Route $route)
    {
        $this->routes[$route->uri] = [
            'uri' => $route->uri,
            'name' => $route->name,
            'view' => $route->view,
            'data' => $route->data,
            'sequence' => $route->sequence,
            'strategy' => $route->strategy,
        ];

        if($route->name) {
            $this->namedRoutes[$route->name] = $route->uri;
        }

        return $this;
    }
}