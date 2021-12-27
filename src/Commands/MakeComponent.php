<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeComponent extends Command
{
    protected $signature = 'make:component {name?}';

    protected $description = 'Create a new view component class';

   public function handle()
   {
        (new Filesystem)
            ->makeDirectory(path('cwd') . '/app/View/Components', 0777, true, true);

        $name = $this->argument('name');

        if(!$name) {
            $name = $this->ask('What should the component be named?');
        }

        if(empty($name)) {
            $this->error('The component must have a name, please try again.');
            return;
        }

        $className = Str::studly($name);
        $viewName = Str::slug($name);

        $template = Str::replace('{{ CLASS_NAME }}', $className, (new Filesystem)->get( __DIR__ . '/stubs/Component.stub'));

        $template = Str::replace('{{ VIEW_NAME }}', $viewName, $template);

        (new Filesystem)
            ->put(
                $classPath = path('cwd') . '/app/View/Components/' . $className . '.php', 
                $template,
            );

        (new Filesystem)
            ->put(
                $viewPath = path('views') . '/components/' . $viewName . '.blade.php', 
                (new Filesystem)->get( __DIR__ . '/stubs/view.stub'),
            );
        
        $this->info(PHP_EOL . 'Component created!' . PHP_EOL);
        $this->info('Class: ' . $classPath);
        $this->info('View: ' . $viewPath);
   }
}