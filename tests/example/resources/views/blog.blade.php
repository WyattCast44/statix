@extends('layouts.base')

@section('page::title', 'My Blog')

@section('body')

    A listing of my blog posts, i want these to be driven by dynamically listing the posts i query.

    <ul class="mt-5 space-y-1">

        @foreach ($posts as $post)
            <li><a class="p-2 rounded hover:bg-gray-200 w-full block -mx-2 hover:no-underline" href="#">
                <div>
                    <h2 class="font-semibold">Post Title #{{ $post['id'] }}</h2>
                    <p class="text-sm">{{ $post['excerpt'] }}</p>
                </div>
            </a></li>
        @endforeach
    </ul>

@endsection