<?php

namespace Statix\Commands;

use Spatie\Watcher\Watch;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Statix\Actions\LoadConfigFiles;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class WatchCommand extends Command
{
    protected $signature = 'watch {build=local} {--serve}';

    protected $description = 'Watch your application resources and rebuild when any changes are made';

    public function handle()
    {
        $this->info(PHP_EOL . 'Watching your application for changes');

        try {
            $this->callSilently('build', [
                'name' => $this->argument('build'),
            ]);
        } catch (\Throwable $th) {
            throw $th;
            exit;
        }

        if($this->option('serve')) {

            $path = path_join('builds', '/', $this->argument('build'));

            $this->info(PHP_EOL . 'Starting development server');
            $this->line('===============================');
            $this->line('Build: ' . $path);
            $this->line('URL: ' . 'http://localhost:8080');

            chdir($path);

            $process = $this->startProcess();

            chdir(__DIR__);

        } 

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
            path('public'),
            path('routes'),
            path('config'),
        )->onAnyChange(function(string $type, string $path) {  

            if(Str::startsWith($path, Str::replace('/', '\\', path('config')))) {
                $this->info(PHP_EOL . 'Reloading config files');

                app()->make(LoadConfigFiles::class)->execute();
            }

            $this->call('build', [
                'name' => $this->argument('build'),
            ]);

        })->start();
    }

    /**
     * Get the full server command.
     *
     * @return array
     */
    protected function serverCommand()
    {
        return [
            (new PhpExecutableFinder)->find(false),
            '-S',
            'localhost:8080',
        ];
    }

    /**
     * Start a new server process.
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function startProcess()
    {
        $process = new Process($this->serverCommand(), null, collect($_ENV)->mapWithKeys(function ($value, $key) {
            return in_array($key, [
                'APP_ENV',
                'LARAVEL_SAIL',
                'PHP_CLI_SERVER_WORKERS',
                'XDEBUG_CONFIG',
                'XDEBUG_MODE',
            ]) ? [$key => $value] : [$key => false];
        })->all());

        $process->start();

        return $process;
    }
}