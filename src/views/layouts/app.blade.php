<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('vendor/laravel-crudgen/images/laravel.svg') }}" type="image/x-icon">
    <title>@yield('title') | Webdecode-Crudgen</title>
</head>
<body class="bg-light">
    @include('laravel-crudgen::partials.css')
    @include('laravel-crudgen::partials.header')
    @yield('content')
    @include('laravel-crudgen::partials.footer')
    @include('laravel-crudgen::partials.js')
</body>
</html>