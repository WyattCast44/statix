@extends('layouts.base')

@section('page::title', 'Home')

@section('body')

    <div class="mb-10">
        <img src="hero.jpg" alt="Welcome to my blog">
    </div>

    <div class="w-full prose max-w-none prose-slate">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae quasi nemo impedit non soluta? Quam consequatur eum doloremque perspiciatis eligendi, ullam aspernatur vitae, rem nostrum tempore error numquam? Ab, eos.</p>

        <p>Lorem ipsum dolor sit amet consectetur <a href="#">adipisicing elit</a>. Doloribus mollitia, necessitatibus quibusdam in eum excepturi ut fuga impedit magnam omnis! Quam eum placeat eos repellendus vitae maiores voluptatum asperiores illum?</p>

        <p>From config: {{ config('site.name') }}</p>

    </div>
    
@endsection