<?php

use Illuminate\Support\Facades\Http;
use Statix\Routing\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/blog', 'blog', [
    'posts' => [
        [
            'id' => 1,
            'slug' => '2320-make-things',
            'excerpt' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi nemo impedit non soluta?',
        ],
        [
            'id' => 2,
            'slug' => '3831-eat-things',
            'excerpt' => 'Lorem ipsum dolor sit amet elit. Recusandae quasi nemo impedit non soluta?',
        ],
    ],
])->name('blog.index');

// Route::sequence('/posts/{post:slug}', 'posts.show', function () {
//     return [
//         [
//             'id' => 1,
//             'slug' => '2320-make-things',
//             'excerpt' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi nemo impedit non soluta?',
//         ],
//         [
//             'id' => 2,
//             'slug' => '3831-eat-things',
//             'excerpt' => 'Lorem ipsum dolor sit amet elit. Recusandae quasi nemo impedit non soluta?',
//         ],
        
//     ];
// })->name('posts.show');

/**
 * Notes
 * - while registering with registrar, check if has dynamic portions, if so and no sequence is given raise error
*/ 

// view
// Route::define('/uri', 'view.name')->name('invokable.controller.example');
// Route::define('/uri/{post:slug}', 'view.name')->sequence($data)->name('invokable.controller.example');

// invokable controller
// Route::define('/uri', InvokableController::class)->name('invokable.controller.example');
// Route::define('/uri/{post:slug}', InvokableController::class)->sequence($data)->name('invokable.controller.example');

// normal controller 
// Route::define('/uri', [NormalContoller::class, 'method'])->name('normal.controller.example');
// Route::define('/uri/{post:slug}', [NormalContoller::class, 'method'])->sequence($data)->name('normal.controller.example');