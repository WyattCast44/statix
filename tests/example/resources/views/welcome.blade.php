@extends('layouts.base')

@section('body')

    <h1>Hello!</h1>

    <ul>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
    </ul>

@endsection