<?php

// use Dotenv\Dotenv;
// use Statix\Application;
// use Illuminate\Config\Repository;
// use Illuminate\Events\Dispatcher;
// use Sabre\Support\PathRepository;
// use Illuminate\Console\Application;
// use Illuminate\Container\Container;
// use Illuminate\View\FileViewFinder;
// use Illuminate\Filesystem\Filesystem;

require_once './vendor/autoload.php';

// $container = tap(new Container, function ($container) {

//     $container->setInstance($container);

//     // Bind paths...
//     $container->singleton('paths', function () {
//         return new PathRepository;
//     });

//     // Set paths...
//     $paths = tap($container->make('paths'), function ($repo) {
//         $repo->set('cwd', getcwd());
//         $repo->set('env_file', $repo->get('cwd') . '/.env');
//         $repo->set('config_path', $repo->get('cwd') . '/config');
//         $repo->set('storage_path', $repo->get('cwd') . '/storage');
//         $repo->set('view_cache', $repo->get('storage') . '/framework/views');
//     });

//     // Load env...
//     if (file_exists($paths->get('env_file'))) {
//         (Dotenv::createImmutable($paths->get('cwd')))->load();
//     }

//     // Bind and set config
//     $container->singleton('config', function () use ($paths) {

//         $path = $paths->get('config_path');

//         // Only accept .php files
//         $items = collect(scandir($path))
//             ->reject(function ($file) {
//                 return is_dir($file);
//             })->reject(function ($file) {
//                 return (pathinfo($file)['extension'] != 'php');
//             })->mapWithKeys(function ($file) use ($path) {
//                 return [basename($file, '.php') => require $path . '/' . $file];
//             })->toArray();

//         return new Repository($items);
//     });

//     // Bind the application...
//     $container->singleton(Application::class, function ($container) {
//         return new Application(
//             $container,
//             new Dispatcher($container),
//             $container->config->get('app.version', '1.0.0')
//         );
//     });

//     $container->bind('app', function ($container) {
//         $container->make(Application::class);
//     });

//     // Bind filesystem...
//     $container->bind('filesystem', function () {
//         return new Filesystem;
//     });

//     // Register custom view locations
//     $viewFinder = new FileViewFinder($container->make('filesystem'), [
//         $paths->get('view_cache'),
//         $paths->get('cwd'),
//     ]);

//     // $viewFactory = new Factory($viewResolver, $viewFinder, new Dispatcher($container));
//     // $container->instance('view_factory', $viewFactory);
// });

// dd($container);
