@extends('layouts.base')

@section('page::title', 'Home')

@section('body')

    <div class="w-full p-8 prose-xl rounded bg-opacity-80 prose-invert max-w-none prose-p:mb-2 prose-h2:mb-4 bg-slate-900">

        <h2>Greetings,</h2>

        <p>
            Welcome to my home on the information super highway. I'm Wyatt, the creator of this space - I'm a full stack web developer 🥞, future space traveler 👨‍🚀, and  father 🐣. I like gifs, dogs 🐕‍🦺, and nice dark editor theme 👨‍💻. I'm currently booking Laravel contract work too, <a href="#">interested in working together</a>?
        </p>
        
    </div>

    <div class="w-full p-8 mt-10">

        <div class="flex items-center pb-4 space-x-3 border-b-4 border-dashed border-slate-200">
            <img src="{{ asset('art/planet-rings-md.svg') }}" class="w-auto h-10 pt-1" />
            <div class="prose prose-xl prose-invert"><h2>Recent Writings</h2></div> 
        </div>

        <div class="prose prose-xl prose-invert prose-a:text-slate-100 prose-a:no-underline">

            <ul>
                <li><a href="#">My Blog thru the years (a glowup? 🌟)</a></li>
                <li><a href="#">Living a delibrate live</a></li>
                <li><a href="#">Building a Farmhouse Bed Frame</a></li>
            </ul>

        </div>

    </div>

    
@endsection