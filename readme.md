# Statix SSG ⚔

Statix is a powerful Laravel-esque static site generator.

# Features

- built on Laravel components
    - config
    - container
    - cli
    - view
    - more
- register custom paths
- full artisan based cli application
- blade templates with blade components support
- advanced markdown compilation
- more

# Inspirations

- https://github.com/aschmelyun/cleaver/
- https://github.com/mattstauffer/Torch
- https://jigsaw.tighten.co/
- https://usecleaver.com/docs/index.html

# Links

- https://stackoverflow.com/questions/65651169/php-check-if-function-callable-but-not-invokable-object
- https://github.com/spatie/file-system-watcher

# Routing

- route groups
- allowable route strategies
    - [controller::class, 'method']
    - Controller::class (invokable)
    - closure
    - view

# Roadmap

- publishable stubs
- make test command
- smart builds
    - maybe multithread?
    - maybe single changed page builds (if web.php changes, trigger full rebuild)
- assets helper
- mix() function to allow for asset versioning - need to grok mix-manifest.json
- copy public folder into build smartly
- inject page details when building, last edit time, path, etc
- hotreload when 
    - env files change
    - config files change, reload config

# Scratchpad

```php

$finder = tap(new Finder(), function($finder) {
    $finder->ignoreVCS(true);
});

$finder->files()->in(path('content'))->name('*.published.md');

$posts = [];

if ($finder->hasResults()) {
        
    foreach ($finder as $file) {
    
        $object = YamlFrontMatter::parse(file_get_contents($file->getRealPath()));

        array_push($posts, ['body' => $object->body()], $object->matter());
    
    }
}

dd($posts, config('app.env'));

Content::query()
    ->if(config('app.env') == 'local', function() {
        return $this->published();
    });

class Content
{
    public static function query()
    {
        return new self;
    }

    public function if($condition, $cb)
    {
        if($condition) {
            $cb();
        }

        return $this;
    }

    public function published()
    {
        return $this;
    }
}
```