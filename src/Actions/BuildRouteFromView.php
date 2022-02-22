<?php 

namespace Statix\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BuildRouteFromView
{
    protected $cli;

    protected $route;

    public function __construct(Command $command = null, $route)
    {
        $this->cli = $command;

        $this->route = $route;    
    }

    public function execute($name = null)
    {
        $start = microtime(true);

        // ensure the view actually exists
        if(!file_exists($this->route['view_path'])) {
            // $this->cli->error('View does not exist: ' . $this->route['view'] . PHP_EOL);
            return true;
        }

        // check if we are builing the site root index.html
        // if not need to build out directories for nice names
        if($this->route['uri'] === '/') {

            // write the static page to file
            File::put(
                path_build('builds', $name, 'index.html'), 
                view($this->route['view'], $this->route['data'])->render()
            );
            
        } else {
            
            // ensure the directory exists
            File::ensureDirectoryExists(
                path_build('builds', $name, $this->route['uri']), 
                0777, 
                true
            );
            
            // write the static page to file
            File::put(
                path_build('builds', $name, $this->route['uri'], 'index.html'), 
                view($this->route['view'], $this->route['data'])->render()
            );

        }

        // $this->cli->line('Building URI: ' . $this->route['uri'] . ' (' . round(microtime(true) - $start, 4) . 's)');
    }
}