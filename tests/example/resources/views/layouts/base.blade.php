<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ config('meta.description', 'Example description') }}">
    <title>{{ config('meta.title', 'Example Title') }}</title>
</head>
<body>
    @yield('body')
</body>
</html>