# Statix SSG ⚔

Forged by skilled Laravel Artisans with the power of PHP, wielding the Blade, to Illuminate the generation of static sites.

# Testing

To run the application test suite, ensure you have installed both the composer and npm dependencies, then run the following command

```bash
./vendor/bin/phpunit
```

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
- eject to full laravel app (copy views, content, fe asset pipeline, etc)
- build netlify.toml files that include Route::redirects configured (https://docs.netlify.com/routing/redirects/#app)

# Scratchpad

# Builds vs Env

- builds can be anything
- envs will decide what env and/or config files to load and which post processors to run

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

# Misc

- new application
- accept params
- setup collision
- configure container
- init default providers
    - event
    - log 