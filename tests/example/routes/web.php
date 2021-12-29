<?php

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
