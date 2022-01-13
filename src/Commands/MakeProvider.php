<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Statix\Support\Filesystem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class MakeProvider extends Command
{
    protected $signature = 'make:provider {name?}';

    protected $description = 'Create a new service provider';

    public function handle()
    {   
        File::ensureDirectoryExists(path_build('cwd', 'app/Providers'));

        $name = $this->determineName();

        $path = path_build('app_path', 'Providers', $name . '.php');

        $contents = Str::replace('{{ CLASS_NAME }}', $name, Filesystem::get(__DIR__. '/stubs/Provider.stub'));

        Filesystem::put($path, $contents);
        
        $this->info(PHP_EOL . 'Provider created: ' . $path);
    }

    private function determineName(): string
    {
        $name = $this->argument('name');

        if(!$name) {
            $name = $this->ask('What should the provider be named?');
        }

        if(empty($name)) {
            $this->error('The provider must have a name, please try again.');
            exit;
        }

        return Str::studly($name);
    }
}