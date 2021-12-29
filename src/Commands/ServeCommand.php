<?php

namespace Statix\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class ServeCommand extends Command
{
    protected $signature = 'serve {build=local} {port=8080}';

    protected $description = 'Serve your application build with builtin PHP server';

    public function handle()
    {
        try {
            $this->call('build', [
                'name' => $this->argument('build'),
            ]);
        } catch (\Throwable $th) {
            throw $th;
            exit;
        }

        $path = path_join('builds', '/', $this->argument('build'));

        $this->info(PHP_EOL . 'Starting development server');
        $this->line('===============================');
        $this->line('Build: ' . $path);
        $this->line('URL: ' . 'http://localhost:' . $this->argument('port'));

        $this->newLine();

        chdir($path);

        $process = $this->startProcess();

        while ($process->isRunning()) {

            clearstatcache(false, path('env_file'));

            usleep(500 * 1000);
        }

        $status = $process->getExitCode();

        return $status;
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
            'localhost:' . $this->argument('port'),
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

        $process->start(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        return $process;
    }
}