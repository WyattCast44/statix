<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCommand extends Command
{
    protected $signature = 'make:command {name?}';

    protected $description = 'Create a new cli command';

    public function handle()
    {        
        File::ensureDirectoryExists(path_join('cwd', '/app/Console/Commands'));

        $name = $this->determineName();

        $path = path_join('app_path', '/Console/Commands/', $name, '.php');

        $contents = Str::replace('{{ COMMAND_NAME }}', $name, File::get(__DIR__. '/stubs/Command.stub'));

        File::put($path, $contents);
        
        $this->info(PHP_EOL . 'Console command created successfully: ' . $path);
    }

    private function determineName(): string
    {
        $name = $this->argument('name');

        if(!$name) {
            $name = $this->ask('What should the command be named?');
        }

        if(empty($name)) {
            $this->error('The command must have a name, please try again.');
            exit;
        }

        return Str::studly($name);
    }
}