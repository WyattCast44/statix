<?php

use Statix\Routing\Route;

// Route::useFileBasedRouting(true);
// Route::view('/', 'welcome')->name('welcome');
// Route::view('/about', 'about')->name('about');
// Route::view('/new', 'welcome')->name('new');

foreach (range(1, 1000) as $value) {
    Route::view("/{$value}", 'welcome')->name("page.{$value}");
}

// 10 pages = 0.25sec
// 100 pages = 1.2sec