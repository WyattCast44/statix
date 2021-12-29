<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Statix\Support\Filesystem;
use Illuminate\Console\Command;

class MakeComponent extends Command
{
    protected $signature = 'make:component {name?}';

    protected $description = 'Create a new view component class';

   public function handle()
   {
        Filesystem::ensureDirectoryExists(path_join('cwd', '/app/View/Components'));

        $name = $this->determineName();

        $viewName = Str::slug($name);

        $className = Str::studly($name);

        $classTemplate = Str::replace(
            '{{ CLASS_NAME }}', 
            $className, 
            Filesystem::get( __DIR__ . '/stubs/Component.stub')
        );

        $classTemplate = Str::replace(
            '{{ VIEW_NAME }}', 
            $viewName, 
            $classTemplate
        );

        $viewPath = path_join('views', '/components/', $viewName, '.blade.php');

        $classPath = path_join('cwd', '/app/View/Components/', $className, '.php');

        Filesystem::put($classPath, $classTemplate);

        Filesystem::put($viewPath, Filesystem::get(__DIR__ . '/stubs/view.stub'));

        $this->info(PHP_EOL . 'Component created!' . PHP_EOL);
        $this->info('Class: ' . $classPath);
        $this->info('View: ' . $viewPath);
   }

   private function determineName(): string
   {
        $name = $this->argument('name');

        if(!$name) {
            $name = $this->ask('What should the component be named?');
        }

        if(empty($name)) {
            $this->error('The component must have a name, please try again.');
            exit;
        }

        return $name;
   }
}