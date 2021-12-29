<?php 

namespace Statix\Routing;

class Route
{
    public $uri;
    public $view;
    public $data;
    public $name;
    public $sequence;
    public $strategy;

    public static function view($uri, $view, $data = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $view, $data) {
            $instance->uri = $uri;
            $instance->view = $view;
            $instance->data = $data;
            $instance->strategy = 'view';
        });
    }

    public static function sequence($uri, $view, $sequence = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $view, $sequence) {
            $instance->uri = $uri;
            $instance->view = $view;
            $instance->sequence = $sequence;
            $instance->strategy = 'sequence';
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