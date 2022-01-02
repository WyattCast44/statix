<?php 

namespace Statix\Actions;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BuildRouteFromView extends BaseAction
{
    protected $cli;

    protected $route;

    public function __construct(Command $command, $route)
    {
        $this->cli = $command;

        $this->route = $route;    
    }

    public function execute()
    {
        $start = microtime(true);

        // ensure the view actually exists
        if(!file_exists($this->route['view_path'])) {
            $this->cli->error('View does not exist: ' . $this->route['view'] . PHP_EOL);
            return true;
        }

        // check if we are builing the site root index.html
        // if not need to build out directories for nice names
        if($this->route['uri'] === '/') {

            // write the static page to file
            (new Filesystem)->put(
                path_join('builds', '/', $this->cli->argument('name'), '/index.html'), 
                view($this->route['view'], $this->route['data'])
            );
            
        } else {
            
            // ensure the directory exists
            (new Filesystem)->ensureDirectoryExists(
                path_join('builds', '/', $this->cli->argument('name'), '/', $this->route['uri']), 
                0777, 
                true
            );
            
            // write the static page to file
            (new Filesystem)->put(
                path_join('builds', '/', $this->cli->argument('name'), '/', $this->route['uri'], '/index.html'), 
                view($this->route['view'], $this->route['data'])
            );

        }

        $this->cli->line('Building URI: ' . $this->route['uri'] . ' (' . round(microtime(true) - $start, 4) . 's)');
    }
}