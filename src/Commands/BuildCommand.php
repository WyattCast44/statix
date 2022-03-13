<?php

namespace Statix\Commands;

use Throwable;
use Spatie\Async\Pool;
use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Support\Facades\File;
use Statix\Actions\BuildRouteFromView;
use Statix\Actions\BuildRouteTreeFromFileStructrure;

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

        $path = builds_path($this->argument('name'));

        // Clear out any old build of the same name
        File::deleteDirectory($path, true);

        $this->copyPublicAssetsDirectory();

        app(BuildRouteTreeFromFileStructrure::class)->execute(resource_path('content'));

        $this->comment('Build time: ' . round(microtime(true) - $this->buildStart, 4) . 's');
    }

    private function copyPublicAssetsDirectory()
    {
        $start = microtime(true);
        
        File::copyDirectory(public_path(), builds_path($this->argument('name')));

        $this->line('Copying public folder (' . round(microtime(true) - $start, 4) . ')');
    }
}