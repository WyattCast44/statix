@extends('layouts.base')

@section('body')

    <h1>About Us</h1>

    <ul>
        <li><a href="{{ route('welcome') }}">Home</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
    </ul>

@endsection