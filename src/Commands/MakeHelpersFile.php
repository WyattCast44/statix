<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeHelpersFile extends Command
{
    protected $signature = 'make:helpers';

    protected $description = 'Create a new helpers.php file';

    public function isHidden()
    {
        return file_exists(app_path('/helpers.php'));
    }

    public function handle()
    {
        File::ensureDirectoryExists(path_join('app_path'));

        $path = app_path('/helpers.php');

        if(file_exists($path)) {
            $this->error(PHP_EOL . 'File already exists, delete file and try again');
            return;
        }

        $template = File::get( __DIR__ . '/stubs/helpers.stub');

        File::put($path, $template);

        $this->info(PHP_EOL . 'File created successfully: ' . $path);
    }
}