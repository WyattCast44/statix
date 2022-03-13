<?php 

namespace Statix\Actions;

use Statix\Actions\BaseAction;
use Symfony\Component\Finder\Finder;

class FindBuildableFiles extends BaseAction
{
    public function execute($path): array
    {
        if(!is_dir($path)) {
            return [];
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

        $files = [];
        
        if($finder->hasResults()) {
            foreach ($finder as $file) {
                array_push($files, $file->getRealPath());
            }
        }

        return $files;
    }
}
