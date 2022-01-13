<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ config('meta.description') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('meta.title') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="h-screen font-sans text-gray-100 bg-indigo-900 selection:bg-slate-500">
    
    <div class="max-w-2xl mx-4 my-5 sm:mx-auto sm:my-10 md:my-16">

        <header class="flex items-center justify-between pb-5 mb-5 border-b border-gray-300">

            <div class="flex items-center space-x-4 text-3xl font-bold">
                <img src="{{ asset('art/moon-gray-lg.svg') }}" alt="" class="w-auto h-16">
                <h1>
                    @yield('page::title')
                </h1>
            </div>
    
            <ul class="flex items-center space-x-3 text-lg">
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
            </ul>
            
        </header>

        @yield('body')

        <footer class="pt-5 mt-5 border-t border-gray-300">

            <ul class="flex items-center space-x-5 text-sm">
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
                <img src="{{ asset('art/rocket.svg') }}" alt="" class="w-auto h-12">
            </ul>

        </footer>

    </div>

</body>
</html>