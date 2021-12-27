<?php

namespace Statix\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeCommand extends Command
{
    protected $signature = 'make:command {name?}';

    protected $description = 'Create a new Artisan command';

    public function handle()
    {        
        (new Filesystem)
            ->makeDirectory(path('cwd') . '/app/Commands', 0777, true, true);

        $name = $this->argument('name');

        if(!$name) {
            $name = $this->ask('What should the command be named?');
        }

        if(empty($name)) {
            $this->error('The command must have a name, please try again.');
            return;
        }

        $name = Str::studly($name);

        (new Filesystem)
            ->put(
                $path = path('cwd') . '/app/Commands/' . $name . '.php', 
                Str::replace('{{ COMMAND_NAME }}', $name, (new Filesystem)->get( __DIR__ . '/stubs/Command.stub'))
            );
        
        $this->info('Command created, ' . $path);
    }
}