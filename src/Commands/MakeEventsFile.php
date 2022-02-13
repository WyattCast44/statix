<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEventsFile extends Command
{
    protected $signature = 'make:events';

    protected $description = 'Create a new events.php file';

    public function isHidden()
    {
        return file_exists(path('routes') . '/events.php');
    }

    public function handle()
    {
        File::ensureDirectoryExists(path('routes'));

        $path = path('routes') . '/events.php';

        if(file_exists($path)) {
            $this->error(PHP_EOL . 'File already exists, delete file and try again');
            return;
        }

        $template = File::get( __DIR__ . '/stubs/events.stub');

        File::put($path, $template);

        $this->info(PHP_EOL . 'File created successfully: ' . $path);
    }
}