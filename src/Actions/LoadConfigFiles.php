<?php 

namespace Statix\Actions;

use Statix\Actions\BaseAction;
use Statix\Events\ConfigFilesLoaded;

class LoadConfigFiles extends BaseAction
{
    public function execute(): void
    {
        $path = $this->app->make('paths')->get('config');

        $items = collect(scandir($path))
            ->reject(function ($file) {
                return is_dir($file);
            })->reject(function ($file) {
                return (pathinfo($file)['extension'] != 'php');
            })->mapWithKeys(function ($file) use ($path) {
                return [basename($file, '.php') => require $path . '/' . $file];
            })->toArray();

        $config = tap($this->app->make('config'))->set($items);

        event(new ConfigFilesLoaded($config));
    }
}
