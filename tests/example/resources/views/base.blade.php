<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ config('meta.description') }}">
    <title>{{ config('meta.title') }}</title>
</head>
<body>

    <x-hello />

    <x-alert type="error" message="This is a class based component" />

</body>
</html>