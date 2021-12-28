<?php

use StatixTest\Commands\HelloWorld;

return [
    
    'name' => env('APP_NAME', 'Statix SSG Example Applcation'),

    'version' => env('APP_VERSION', '1.0.0'),

    'commands' => [
        HelloWorld::class,
    ],

    'paths' => [
        'test-path' => path('cwd') . '/statix.php',
        'routes' => path('cwd') . '/routes/web.php',
    ],

];