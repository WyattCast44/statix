<?php

namespace Statix\Commands;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Filesystem\Filesystem;

class BuildCommand extends Command
{
    protected $signature = 'build {name=local}';

    protected $description = 'Create a new build of your application';

    public function handle()
    {        
        $buildStart = microtime(true);

        $this->info(PHP_EOL . 'Building your site (' . $this->argument('name') . ')');
        $this->line('===============================');

        require path('cwd') . '/routes/web.php';

        $routes = collect(container()->make(RouteRegistrar::class)->routes);

        (new Filesystem)->deleteDirectory(path_join('builds', '/', $this->argument('name')));

        (new Filesystem)->ensureDirectoryExists(path_join('builds', '/', $this->argument('name')), 0777, true);

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
                    
                    (new Filesystem)->put(
                        path_join('builds', '/', $this->argument('name'), '/index.html'), 
                        view($route['view'], $route['data'])
                    );
                    
                } else {
                    
                    (new Filesystem)->ensureDirectoryExists(path_join('builds', '/', $this->argument('name'), '/', $uri), 0777, true);
                    
                    (new Filesystem)->put(
                        path_join('builds', '/', $this->argument('name'), '/', $uri, '/index.html'), 
                        view($route['view'], $route['data'])
                    );

                }

                $this->line('Building URI: ' . $uri . ' (' . round(microtime(true) - $start, 4) . 's)');

                return true;
            }

            if($route['strategy'] === 'sequence') {
                
                $this->error('TODO');
                return true;

                // if(gettype($route['sequence']) === 'array') {

                    
                //     foreach ($route['sequence'] as $value) { 
                //         $start = microtime(true);

                //         $resource = substr(
                //             $uri, 
                //             strpos($uri, '{') + 1, 
                //             strlen(substr($uri, strpos($uri, '}'))) - 1
                //         );
                        
                //         extract([$resource]);
                        
                //         // dd([$resource => $value]);

                //         $this->line('Building URI: ' . substr_replace($uri, $value, strpos($uri, '{'), strpos($uri, '}')) . ' (' . round(microtime(true) - $start, 4) . ')');
                //     }

                // }

                // if($route['sequence'] instanceof Closure) {
                //     //
                // }

                // return true;

            }

        });

        container()->make(RouteRegistrar::class)->routes = [];
        
        $this->comment('Build time: ' . round(microtime(true) - $buildStart, 4) . 's');

    }

    private function copyPublicAssetsDirectory()
    {
        $start = microtime(true);
        
        (new Filesystem)->copyDirectory(path_join('assets', '/public'), path_join('builds', '/', $this->argument('name')));

        $this->line('Copying public folder (' . round(microtime(true) - $start, 4) . ')');
    }
}