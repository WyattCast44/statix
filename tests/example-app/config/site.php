<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is displayed when 
    | the framework is called from the CLI, or as required by the application 
    | or its packages.
    |
    */

    'name' => env('APP_NAME', 'Statix SSG Example Applcation'),

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value is the version of your application. This value is displayed when 
    | the framework is called from the CLI, or as required by the application 
    | or its packages.
    |
    */

    'version' => env('APP_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    | Allowable values: local, production, testing
    |
    */

    'env' => env('APP_ENV', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the application to properly generate URLs when using
    | the route() and asset() helper function. You should set this to the root 
    | of your application so that it is used when creating application builds 
    | and when running the serve command.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Port
    |--------------------------------------------------------------------------
    |
    | This port is used by the application to properly generate URLs when using
    | the route() and asset() helper function. Additionally when using the 
    | serve command, your site build will be served on this port at the 
    | url above.
    |
    */

    'port' => env('APP_PORT', '8080'),
    
    /*
    |--------------------------------------------------------------------------
    | Application CLI Commands
    |--------------------------------------------------------------------------
    |
    | The commands listed here will be automatically registred with the
    | application CLI. Feel free to add your own commands to this array  
    | to grant expanded functionality to your applications.
    |
    */

    'commands' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Paths
    |--------------------------------------------------------------------------
    |
    | The paths listed here will be automatically registred with the 
    | application path manager. Feel free to add your paths to this array to 
    | easily access them with the path() helper function.
    |
    */

    'paths' => [
        //
    ],

];