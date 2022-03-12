<?php

use Statix\Routing\Route;

// Route::useFileBasedRouting(resource_path('content'));
// Route::view('/', 'welcome')->name('welcome');
// Route::view('/about', 'about')->name('about');
// Route::view('/new', 'welcome')->name('new');

// foreach (range(1, 10) as $value) {
//     Route::view("/{$value}", 'welcome')->name("page.{$value}");
// }

// 10 pages = 0.25sec
// 100 pages = 1.5sec
// 1000 pages = 15sec
// 2000 pages = 47sec
// 10000 pages = 138sec