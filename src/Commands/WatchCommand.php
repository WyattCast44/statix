<?php

namespace Statix\Commands;

use Spatie\Watcher\Watch;
use Illuminate\Console\Command;

class WatchCommand extends Command
{
    protected $signature = 'watch';

    protected $description = 'Watch your application resources and rebuild when any changes are made';

    public function handle()
    {
        $this->info(PHP_EOL . 'Watching your application for changes');

        try {
            $this->call('build');
        } catch (\Throwable $th) {
            throw $th;
            exit;
        }

        Watch::paths(
            path('views'),
            path('routes'),
            path('assets'),
        )->onAnyChange(function(string $path) {  
            $this->call('build');
        })->start();
    }
}