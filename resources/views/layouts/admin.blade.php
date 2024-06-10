<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Boolfolio') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="admin">
        @include('admin.partials.header')


        <div id="wrapper">
            <div id="sidebar">
                <div class="ulSide">
                   <a class="btn " href="{{ route('admin.dashboard')}}">Dashboard</a>
                   <a class="btn " href="{{ route('admin.project.index')}}">Projects</a>
                   <a class="btn " href="{{ route('admin.types.index')}}">Types</a>
                   <a class="btn " href="{{ route('admin.technologies.index')}}">Technologies</a>
                </div>
            </div>
            <main id="main">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>