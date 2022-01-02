<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Filesystem\Filesystem;
use Statix\Actions\BuildRouteFromView;

class BuildCommand extends Command
{
    protected $buildStart;

    protected $signature = 'build {name=local}';

    protected $description = 'Create a new build of your application';
    
    public function handle()
    { 
        $this->buildStart = microtime(true);

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

                (new BuildRouteFromView($this, $route))->execute();
            
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

        $this->comment('Build time: ' . round(microtime(true) - $this->buildStart, 4) . 's');
    }

    private function copyPublicAssetsDirectory()
    {
        $start = microtime(true);
        
        (new Filesystem)->copyDirectory(path_join('assets', '/public'), path_join('builds', '/', $this->argument('name')));

        $this->line('Copying public folder (' . round(microtime(true) - $start, 4) . ')');
    }
}