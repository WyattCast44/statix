<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Statix\Routing\RouteRegistrar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class BuildHttpCommand extends Command
{
    protected $buildStart;

    protected $signature = 'build-http {name=local}';

    protected $description = 'Create a new build of your application';
    
    public function handle()
    { 
        $this->buildStart = microtime(true);

        try {
            $this->callSilently('build', [
                'name' => $this->argument('name'),
            ]);
        } catch (\Throwable $th) {
            throw $th;
            exit;
        }

        $path = path_join('builds', '/', $this->argument('name'));

        $this->info(PHP_EOL . 'Starting development server');
        $this->line('===============================');
        $this->line('Build: ' . $path);
        $this->line('URL: ' . 'http://localhost:8080');

        chdir($path);

        $process = $this->startProcess();

        chdir(__DIR__);

        $this->info(PHP_EOL . 'Building your site (' . $this->argument('name') . ')');
        $this->line('===============================');

        $uris = collect([
            '/',
            '/about',
            '/contact',
        ])->each(function($uri) {

            $url = config('site.url') . ':' . config('site.port') . $uri;

            $reponse = Http::get($url);

            // dd($reponse->body());

        });

        $this->comment('Build time: ' . round(microtime(true) - $this->buildStart, 4) . 's');
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