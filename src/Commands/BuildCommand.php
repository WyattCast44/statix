<?php

namespace Statix\Commands;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Filesystem\Filesystem;

class BuildCommand extends Command
{
    protected $signature = 'build';

    protected $description = 'Create a new build of your application';

    public function handle()
    {        
        $buildStart = microtime(true);

        $this->info(PHP_EOL . 'Building your site');
        $this->line('===============================');

        require path('cwd') . '/routes/web.php';

        $routes = collect(container()->make(RouteRegistrar::class)->routes);

        (new Filesystem)->deleteDirectory(path_join('builds', '/prod'));

        (new Filesystem)->ensureDirectoryExists(path_join('builds', '/prod'), 0777, true);

        $this->copyPublicAssetsDirectory();
        
        $routes->each(function($route, $uri) {
            
            if($route['strategy'] === 'view') {
                
                $start = microtime(true);
                
                $path = path_join('views', '/', Str::replace('.', '/', $route['view']), '.blade.php');

                if(!file_exists($path)) {
                    $this->error('View does not exist: ' . $route['view'] . PHP_EOL);
                    return true;
                }

                if($uri === '/') {
                    
                    // create root index.html
                    (new Filesystem)->put(path('builds') . '/prod/index.html', view($route['view'], $route['data']));
                    
                } else {
                    
                    (new Filesystem)->ensureDirectoryExists(path_join('builds', '/prod/', $uri), 0777, true);
                    (new Filesystem)->put(path('builds') . '/prod/' . $uri . '/index.html', view($route['view'], $route['data']));

                }

                $this->line('Building URI: ' . $uri . ' (' . round(microtime(true) - $start, 4) . 's)');

                return true;
            }

            if($route['strategy'] === 'handler') {
                
                $this->line('Building URI: ' . $uri);
                
                dd(
                    $route['handler'],
                    $route['handler'] instanceof Closure, 
                    class_exists($route['handler']),
                );

                if($uri === '/') {
                    
                    // create root index.html
                    (new Filesystem)->makeDirectory(path('builds') . '/prod', 0777, true, true);
                    (new Filesystem)->put(path('builds') . '/prod/index.html', $route['handler']() , $route['data']);

                    
                } else {

                    (new Filesystem)->makeDirectory(path('builds') . '/prod/' . $uri, 0777, true, true);
                    (new Filesystem)->put(path('builds') . '/prod/' . $uri . '/index.html', $route['handler'](), $route['data']);

                }

            }

        });

        container()->make(RouteRegistrar::class)->routes = [];
        
        $this->comment('Build time: ' . round(microtime(true) - $buildStart, 4) . 's');

    }

    private function copyPublicAssetsDirectory()
    {
        $start = microtime(true);
        
        (new Filesystem)->copyDirectory(path_join('assets', '/public'), path_join('builds', '/prod'));

        $this->line('Copying public folder (' . round(microtime(true) - $start, 4) . ')');
    }
}