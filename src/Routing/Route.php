<?php 

namespace Statix\Routing;

class Route
{
    public $uri;
    public $view;
    public $handler;
    public $data;
    public $name;
    public $sequence;
    public $strategy;

    public static function define($uri, $handler, $data = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $handler, $data) {
            $instance->uri = $uri;
            $instance->handler = $handler;
            $instance->data = $data;
            $instance->strategy = 'handler';
        });
    }

    public static function view($uri, $view, $data = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $view, $data) {
            $instance->uri = $uri;
            $instance->view = $view;
            $instance->data = $data;
            $instance->strategy = 'view';
        });
    }

    public function name($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __destruct()
    {
        container()->make(RouteRegistrar::class)->add($this);
    }
}