<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');