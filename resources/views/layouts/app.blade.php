<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>@yield('title') - {{ config('app.name') }}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ mix('/css/mixed.css') }}" rel="stylesheet">
    </head>
    <body>
        <header class="ta-center">
            <h1>@yield('title')</h1>
        </header>

        <main class="dick wrapper-large no-top-padding some-bottom-padding">
            @yield('content')
        </main>

        <div class="text-browser"><br></div>

        <footer>
            <p>Dates and times are {{ Config::get('app.timezone') }}.</p>

            @includeIf('footer')
        </footer>
    </body>
</html>
