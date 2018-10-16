<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>@yield('title') - {{ config('app.name') }}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="/cutestrap.min.css" rel="stylesheet">
        <style>
            input[type="text"] { padding-top: 0.8rem!important; }
            .no-top-padding { padding-top: 0!important; }
            .no-bottom-margin { margin-bottom: 0!important; }
            .some-bottom-margin { margin-bottom: 0.5rem!important; }
            .some-bottom-padding { padding-bottom: 3rem!important; }
            .some-top-margin { margin-top: 2.0rem; }
            .inline-block { display: inline-block; }

            .ta-center {
                margin-top: 1rem;
            }

            .post-input-label {
                display: inline-block;
                font-size: 1.2rem;
            }

            .number {
                display: inline-block;
                margin-bottom: 2.4rem;
                padding: 0 1rem 0 2rem;
                position: relative;
            }

            .number > input[type="number"] {
                display: inline-block;
                border: 1px solid #7d7d7e;
                margin-top: 0.5rem;
                margin-left: 1rem;
            }

            .number > input[type="number"]:focus {
                border-color: #e83fb8;
                box-shadow: 0 1px 2px 0 #dededf inset;
                outline: 0;
            }

            input[type="datetime-local"] {
                margin-top: 0.2rem;
                margin-bottom: 0.2rem;
                font-size: 1.3rem;
                padding: 0.2rem 0.5rem 0 0.5rem!important;
                display: inline-block;
                border: 1px solid #7d7d7e;
            }

            input[type="datetime-local"]:focus {
                border-color: #e83fb8;
                box-shadow: 0 1px 2px 0 #dededf inset;
                outline: 0;
            }

            /* TODO: Fix footer: should stick to bottom (with some padding/margin at the bottom) and centered */

            body {
                min-height: initial;
            }

            footer, footer > * {
                display: flex;
                justify-content: center;
                padding-bottom: 0;
            }

            input.inline-text[type="text"] {
                margin-top: 0.2rem;
                margin-bottom: 0.2rem;
                padding: 0.2rem 0.5rem 0 0.5rem!important;
                font-size: 1.3rem;
                display: inline-block;
                border: 1px solid #7d7d7e;
            }

            input.inline-text[type="text"]:focus {
                border-color: #e83fb8;
                box-shadow: 0 1px 2px 0 #dededf inset;
                outline: 0;
            }
            </style>
    </head>
    <body>
        <header class="ta-center">
            <h1>@yield('title')</h1>
        </header>

        <main class="dick wrapper-large no-top-padding some-bottom-padding">
            @yield('content')
        </main>

        <footer>
            <p>Dates and times are UTC.</p>
        </footer>
    </body>
</html>
