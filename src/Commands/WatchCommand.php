<?php

namespace Statix\Commands;

use Spatie\Watcher\Watch;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class WatchCommand extends Command
{
    protected $signature = 'watch {build=local}';

    protected $description = 'Watch your application resources and rebuild when any changes are made';

    public function handle()
    {
        $this->info(PHP_EOL . 'Watching your application for changes');

        try {
            $this->call('build', [
                'name' => $this->argument('build'),
            ]);
        } catch (\Throwable $th) {
            throw $th;
            exit;
        }

        Watch::paths(
            path('views'),
            path('routes'),
            path('assets'),
            path('config'),
        )->onAnyChange(function(string $type, string $path) {  

            if(Str::startsWith($path, path('config'))) {
                $this->info(PHP_EOL . 'Reloading config files');

                app()->reloadConfigFiles();
            }

            $this->call('build', [
                'name' => $this->argument('build'),
            ]);

        })->start();
    }
}