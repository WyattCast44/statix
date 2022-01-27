<?php

namespace Statix\Commands;

use Illuminate\Console\Command;

class ClearCompiledViews extends Command
{
    protected $signature = 'view:clear';

    protected $description = 'Clear compiled templates/views';

    public function handle()
    {
        array_map('unlink', array_filter((array) glob(path('view_cache') . '/*.php')));

        $this->info(PHP_EOL . 'Cleared compiled templates/views successfully');
    }
}