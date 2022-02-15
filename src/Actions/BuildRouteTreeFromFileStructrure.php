<?php 

namespace Statix\Actions;

use Statix\Actions\BaseAction;
use Statix\Builder\Page;
use Statix\Events\ConfigFilesLoaded;
use Symfony\Component\Finder\Finder;

class BuildRouteTreeFromFileStructrure extends BaseAction
{
    public function execute(): void
    {
        $path = $this->app->make('paths')->get('content');

        if(!is_dir($path)) {
            return;
        }

        $finder = tap(new Finder(), function($finder) {
            $finder->ignoreVCS(true);
        });
        
        $finder->files()->in($path)->name([
            '*.php', 
            '*.blade.php', 
            '*.blade.md',
            '*.md',
            '*.markdown',
            '*.mdown',
            '*.html',
        ]);
        
        $pages = [];
        
        if ($finder->hasResults()) {
                
            foreach ($finder as $file) {

                $page = new Page(
                        $file->getRealPath(), 
                        $file->getContents()
                );

                array_push($pages, $page);                
            
            }
        }

        dd($pages);
    }
}
