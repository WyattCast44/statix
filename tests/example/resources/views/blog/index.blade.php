@extends('layouts.base')

@section('body')

    <h1>My Blog - Index</h1>

    <ul>
        <li><a href="{{ route('welcome') }}">Home</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
    </ul>

@endsection