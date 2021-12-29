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
- copy public folder into build
- inject page details when building, last edit time, path, etc