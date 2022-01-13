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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turbolinks/5.0.0/turbolinks.min.js" defer></script>
</head>
<body class="h-screen overflow-hidden font-sans text-gray-100 bg-gradient-to-br from-slate-800 to-indigo-800 selection:bg-slate-500">

    <div class="absolute inset-0 bg-opacity-75 -z-10" style="background-image: url({{ asset('art/stars.gif') }})">
    </div>

    <div>
        <img src="{{ asset('art/green-planet-md.svg') }}" class="absolute animate-wiggle top-[24px] right-[34px] w-auto h-12">
    </div>

    <div class="relative z-50">

        <div class="max-w-2xl mx-4 my-5 sm:mx-auto sm:my-10 md:my-16">
    
            <header class="flex items-center justify-between pb-5 mb-5">
    
                <div class="flex items-center space-x-4 text-3xl font-bold">
                    <img src="{{ asset('art/moon-gray-lg.svg') }}" alt="" class="w-auto h-16 select-none animate-[spin_10s_ease-in-out_infinite] drop-shadow">
                    <h1 class="drop-shadow">
                        Wyatt's Blog 
                    </h1>
                </div>
        
                <ul class="flex items-center space-x-3 text-lg">
                    <li><a href="{{ route('welcome') }}" class="transition duration-75 hover:bg-indigo-600 px-2.5 py-1.5 hover:no-underline hover:bg-opacity-50">Home</a></li>
                    <li><a href="{{ route('about') }}" class="transition duration-75 hover:bg-indigo-600 px-2.5 py-1.5 hover:no-underline hover:bg-opacity-50">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="transition duration-75 hover:bg-indigo-600 px-2.5 py-1.5 hover:no-underline hover:bg-opacity-50">Contact Us</a></li>
                </ul>
                
            </header>
    
            @yield('body')
    
            {{-- <footer class="pt-5 mt-5 border-t border-gray-300">
    
                <ul class="flex items-center space-x-5 text-sm">
                    <li><a href="{{ route('welcome') }}">Home</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <img src="{{ asset('art/flaming-astroid.svg') }}" alt="" class="w-auto h-12 select-none">
                </ul>
    
            </footer> --}}
    
            <div class="fixed bottom-0 right-0">
                <img src="{{ asset('art/rocket.svg') }}" alt="" class="relative h-16 top-[139px] left-[200px]">
                <img src="{{ asset('art/astronaut.svg') }}" alt="" class="relative h-24 top-[15px] left-[90px] -skew-x-2 drop-shadow-lg" x-data x-on:click="$el.classList.toggle('animate-bounce')">
                <img src="{{ asset('art/peak-group.svg') }}" alt="" class="w-80 drop-shadow-xl">
            </div>
    
        </div>
    </div>

</body>
</html>