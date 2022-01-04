<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearBuilds extends Command
{
    protected $signature = 'builds:clear';

    protected $description = 'Clear previous builds';

    public function handle()
    {
        (new Filesystem)->deleteDirectory(path('builds'), true);

        $this->info(PHP_EOL . 'Cleared builds!');
    }
}