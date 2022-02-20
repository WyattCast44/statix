<?php

use Statix\Routing\Route;

// Route::useFileBasedRouting(true);
Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');
Route::view('/new', 'welcome')->name('new');
