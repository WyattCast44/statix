<?php

namespace Statix\Commands;

use Throwable;
use Spatie\Async\Pool;
use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Support\Facades\File;
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

        $path = base_path("builds/{$this->argument('name')}");
                
        // Clear out any old build of the same name
        File::deleteDirectory($path);

        // Ensure build directory exists
        File::ensureDirectoryExists($path, 0777, true);

        // Copy any public assets, css, js, favicon, etc
        $this->copyPublicAssetsDirectory();
        
        require_once base_path('routes/web.php');

        $routes = app()->make(RouteRegistrar::class)->routes;

        $pool = Pool::create();

        $pool->concurrency(10);

        foreach ($routes as $route) {
            $pool->add(function () use ($route) {
                (new BuildRouteFromView(null, $route))->execute($this->argument('name'));
            })->then(function ($output) {
                // Handle success
            })->catch(function (Throwable $exception) {
                // Handle exception
            });
        }

        $pool->wait();

        collect(app()->make(RouteRegistrar::class)->routes)->each(function($route, $uri) {
            
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

        app()->make(RouteRegistrar::class)->routes = [];

        $this->comment('Build time: ' . round(microtime(true) - $this->buildStart, 4) . 's');
    }

    private function copyPublicAssetsDirectory()
    {
        $start = microtime(true);
        
        File::copyDirectory(public_path(), base_path("builds/{$this->argument('name')}"));

        $this->line('Copying public folder (' . round(microtime(true) - $start, 4) . ')');
    }
}