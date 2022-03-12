<?php 

namespace Statix\Actions;

use Statix\Builder\Page;
use Illuminate\Support\Str;
use Statix\Actions\BaseAction;
use Statix\Routing\Route;
use Symfony\Component\Finder\Finder;

class BuildRouteTreeFromFileStructrure extends BaseAction
{
    public function execute($path): void
    {
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

        $page = collect($pages)->first();
        dd($page->getExtension());
    }
}
