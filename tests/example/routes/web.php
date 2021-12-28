<?php

use Statix\Routing\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');

// Route::define('/blog', function() {
//     return view('blog.index');
// })->name('blog.index');

// Route::define('/blog/posts', function() {
// })->name('blog.posts.index');

// Route::view('/', 'welcome')->name('welcome');
// Route::view('/blog', 'blog.index')->name('blog.index');

// Route::define('/blog/posts/{post:slug}', function($slug) {
//     return view('blog.posts.show', [
//         'post' => ''
//     ]);
// })->sequence(function() {

// })->name('blog.posts.show');

// Route::view('/path', 'view')->sequence($posts)->name('name');
// Route::define('/path', 'view')->sequence($posts)->name('name');
// ->sequence(function()), cb to return either a collection or a array of file names
// ->sequence([]), array of post file names
// ->sequence(Content::collection()), collection of posts
