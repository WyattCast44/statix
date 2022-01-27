<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeComponent extends Command
{
    protected $signature = 'make:component {name?}';

    protected $description = 'Create a new view component class';

   public function handle()
   {
        File::ensureDirectoryExists(path_join('app_path', '/View/Components'));
        File::ensureDirectoryExists(path_join('views', '/components'));

        $name = $this->determineName();

        $viewName = Str::slug($name);

        $className = Str::studly($name);

        $classTemplate = Str::replace(
            '{{ CLASS_NAME }}', 
            $className, 
            File::get( __DIR__ . '/stubs/Component.stub')
        );

        $classTemplate = Str::replace(
            '{{ VIEW_NAME }}', 
            $viewName, 
            $classTemplate
        );

        $viewPath = path_join('views', '/components/', $viewName, '.blade.php');

        $classPath = path_join('app_path', '/View/Components/', $className, '.php');

        File::put($classPath, $classTemplate);

        File::put($viewPath, File::get(__DIR__ . '/stubs/view.stub'));

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