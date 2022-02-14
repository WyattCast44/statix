<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;

// Route::useFileBasedRouting(true);
Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');

$request = new Request();
// $router->view('/', 'welcome')->name('welcome');
// $router->view('/about', 'about')->name('about');

$redirect = new UrlGenerator(app('router')->getRoutes(), $request);
// dd($redirect);