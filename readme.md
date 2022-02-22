# Statix SSG ⚔

Statix is a powerful Laravel-esque static site generator. 

Forged by skilled Laravel Artisans with the power of PHP, wielding the Blade, to Illuminate the generation of static sites.

# Inspirations

- https://github.com/aschmelyun/cleaver/
- https://github.com/mattstauffer/Torch
- https://jigsaw.tighten.co/
- https://usecleaver.com/docs/index.html

# Links

- https://stackoverflow.com/questions/65651169/php-check-if-function-callable-but-not-invokable-object
- https://github.com/spatie/file-system-watcher
- https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/helpers.php
- https://github.com/illuminate/support/blob/master/helpers.php
- https://css-tricks.com/comparing-static-site-generator-build-times/
- https://ssg-build-performance-tests.netlify.app/

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

dd($posts, config('site.env'));

Content::query()
    ->if(config('site.env') == 'local', function() {
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

# Builds vs Env

- builds can be anything
- envs will decide what env and/or config files to load and which post processors to run

# Publish/Eject to full Laravel App

- copy public folder
- copy views
- copy css
- copy js
- copy config (need to merge app.php?, or maybe create new config file in laravel app and save all config to that file?)
- 

# Redirect

```php
Route::redirect('/old', '/new', function() {
    // render an html template with a js redirect
})
```
# Base Kits

- Minimal, no app, no config, no etc
- Tailwind Blog
- Tailwind Docs
- Bootstrap Docs
- Bootstrap Blog

# Fake http request to build pages

- spin up local php server with the document route being a fake index.php
- use http client to make request to page "/about"

# File based routing
- next.js

# Testing

To run the application test suite, run the following command. 

```bash
./vendor/bin/phpunit
```