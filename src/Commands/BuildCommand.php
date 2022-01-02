<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Filesystem\Filesystem;
use Statix\Actions\BuildRouteUsingViewStrategy;

class BuildCommand extends Command
{
    protected $buildStart;

    protected $signature = 'build {name=local}';

    protected $description = 'Create a new build of your application';

    public function __construct()
    {
        parent::__construct();

        $this->buildStart = microtime(true);
    }
    
    public function handle()
    { 
        $this->info(PHP_EOL . 'Building your site (' . $this->argument('name') . ')');
        $this->line('===============================');

        // Clear out any old build of the same name
        (new Filesystem)->deleteDirectory(path_join('builds', '/', $this->argument('name')));

        // Ensure build directory exists
        (new Filesystem)->ensureDirectoryExists(path_join('builds', '/', $this->argument('name')), 0777, true);

        // Copy any public assets, css, js, favicon, etc
        $this->copyPublicAssetsDirectory();
        
        require path('cwd') . '/routes/web.php';

        collect(container()->make(RouteRegistrar::class)->routes)->each(function($route, $uri) {
            
            if($route['strategy'] === 'view'):
                
                $start = microtime(true);

                // build up the path to the view source
                $path = path_join('views', '/', Str::replace('.', '/', $this->route['view']), '.blade.php');

                // ensure the view actually exists
                if(!file_exists($path)) {
                    $this->error('View does not exist: ' . $this->route['view'] . PHP_EOL);
                    return true;
                }

                // check if we are builing the site root index.html
                // if not need to build out directories for nice names
                if($uri === '/') {
        
                    // write the static page to file
                    (new Filesystem)->put(
                        path_join('builds', '/', $this->argument('name'), '/index.html'), 
                        view($route['view'], $route['data'])
                    );
                    
                } else {
                    
                    // ensure the directory exists
                    (new Filesystem)->ensureDirectoryExists(path_join('builds', '/', $this->argument('name'), '/', $uri), 0777, true);
                    
                    // write the static page to file
                    (new Filesystem)->put(
                        path_join('builds', '/', $this->argument('name'), '/', $uri, '/index.html'), 
                        view($route['view'], $route['data'])
                    );

                }

                $this->line('Building URI: ' . $uri . ' (' . round(microtime(true) - $start, 4) . 's)');

                return true;

            endif;

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
    }

    private function copyPublicAssetsDirectory()
    {
        $start = microtime(true);
        
        (new Filesystem)->copyDirectory(path_join('assets', '/public'), path_join('builds', '/', $this->argument('name')));

        $this->line('Copying public folder (' . round(microtime(true) - $start, 4) . ')');
    }

    public function __destruct()
    {
        $this->comment('Build time: ' . round(microtime(true) - $this->buildStart, 4) . 's');
    }
}