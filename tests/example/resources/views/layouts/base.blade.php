<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ config('meta.description', 'Example description') }}">
    <title>{{ config('meta.title', 'Example Title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">
    
    <div class="max-w-2xl sm:mx-auto mx-4 my-5 sm:my-10 md:my-16">

        <header class="flex items-center justify-between mb-5 pb-5 border-b border-gray-300">

            <h1 class="text-3xl font-bold">@yield('page::title')</h1>
    
            <ul class="flex items-center space-x-3 text-lg">
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
            </ul>
            
        </header>

        @yield('body')

        <footer class="pt-5 border-t border-gray-300 mt-5">

            <ul class="text-sm flex items-center space-x-5">
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
            </ul>

        </footer>

    </div>

</body>
</html>