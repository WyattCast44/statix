<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Statix\Routing\RouteRegistrar;

class BuildCommand extends Command
{
    protected $signature = 'build';

    protected $description = 'Create a new build of your application';

    public function handle()
    {        
        $this->info(PHP_EOL . 'Building your site');
        $this->line('============================');

        $routes = collect(container()->make(RouteRegistrar::class)->routes);
        
        $routes->each(function($route, $uri) {
            
            if($route['strategy'] === 'view') {
                
                $this->line('Building URI: ' . $uri . ', View: ' . $route['view']);

                if($uri === '/') {
                    
                    // create root index.html
                    (new Filesystem)->makeDirectory(path('builds') . '/prod', 0777, true, true);
                    (new Filesystem)->put(path('builds') . '/prod/index.html', view($route['view'], $route['data']));
                    
                } else {
                    
                    (new Filesystem)->makeDirectory(path('builds') . '/prod/' . $uri, 0777, true, true);
                    (new Filesystem)->put(path('builds') . '/prod/' . $uri . '/index.html', view($route['view'], $route['data']));

                }
            }
        });

    }
}