<?php 

namespace Statix\Routing;

use Illuminate\Support\Str;

class Route
{
    public $uri;
    public $view;
    public $view_path;
    public $data;
    public $name;
    public $sequence;
    public $strategy;

    public static function view($uri, $view, $data = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $view, $data) {
            $instance->uri = $uri;
            $instance->view = $view;
            $instance->view_path = app()->viewPath(Str::replace('.', '/', $view) . '.blade.php');
            $instance->data = $data;
            $instance->strategy = 'view';
        });
    }

    public static function sequence($uri, $view, $sequence = []): Route
    {
        return tap($instance = new self, function($instance) use ($uri, $view, $sequence) {
            $instance->uri = $uri;
            $instance->view = $view;
            $instance->view_path = app()->viewPath(Str::replace('.', '/', $view) . '.blade.php');
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
        app()->make(RouteRegistrar::class)->add($this);
    }
}