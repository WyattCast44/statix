<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEvent extends Command
{
    protected $signature = 'make:event {name?}';

    protected $description = 'Create a new event class';

   public function handle()
   {
        File::ensureDirectoryExists(path_join('app_path', '/Events'));

        $name = $this->determineName();

        $className = Str::studly($name);

        $classTemplate = Str::replace(
            '{{ CLASS_NAME }}', 
            $className, 
            File::get( __DIR__ . '/stubs/Event.stub')
        );

        $classPath = path_join('app_path', '/Events/', $className, '.php');

        File::put($classPath, $classTemplate);

        $this->info(PHP_EOL . 'Event created successfully: ' . $classPath);
   }

   private function determineName(): string
   {
        $name = $this->argument('name');

        if(!$name) {
            $name = $this->ask('What should the event class be named?');
        }

        if(empty($name)) {
            $this->error('The event class must have a name, please try again.');
            exit;
        }

        return $name;
   }
}