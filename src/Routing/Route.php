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

    public static function define($uri, $handler, $data = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $handler, $data) {
            $instance->uri = $uri;
            $instance->handler = $handler;
            $instance->data = $data;
        });
    }

    public static function view($uri, $view, $data = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $view, $data) {
            $instance->uri = $uri;
            $instance->view = $view;
            $instance->data = $data;
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