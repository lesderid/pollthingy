<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>@yield('title') - {{ config('app.name') }}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="cutestrap.min.css" rel="stylesheet">
        <style>
            input[type="text"] { padding-top: 0.8rem!important; }
            .no-top-padding { padding-top: 0!important; }
        </style>
    </head>
    <body>
        <header class="wrapper ta-center">
            <h1>@yield('title') - {{ config('app.name') }}</h1>
        </header>

        <section class="wrapper-large no-top-padding">
            @yield('content')
        </section>

        <footer>
        </footer>
    </body>
</html>
