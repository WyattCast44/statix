<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearBuilds extends Command
{
    protected $signature = 'builds:clear';

    protected $description = 'Clear previous builds';

    public function handle()
    {
        File::deleteDirectory(path('builds', true));

        $this->info(PHP_EOL . 'Cleared builds successfully');
    }
}