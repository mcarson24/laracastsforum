<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'signedIn'  => auth()->check(),
            'user'      => auth()->user(),
        ]) !!};
    </script>

    <!-- Temporary Styles -->
    <style>
        .level {
            display: flex;
            align-items: center;
        }

        .flex {
            flex: 1;
        }

        .mr-quarter {
            margin-right: .25em;
        }

        .mr-1 {
            margin-right: 1em;
        }

        .mb-1 {
            margin-bottom: 1em;
        }

        .mr-top-25 {
            margin-top: 25px;
        }
        
        .list-group-item {
            border: none;
        }

        .br-5 {
            border-radius: 5px;
        }

        [v-cloak] {
            display: none;
        }

        .disabled {
            pointer-events: none;
            cursor: default;
            color: #DDD;
        }

        .m-l-auto {
            margin-left: auto;
        }
    </style>
    @yield('head-end')
</head>
<body>
    <div id="app">
        @include('layouts._nav')

        @yield('content')

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
