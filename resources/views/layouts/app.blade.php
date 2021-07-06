<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')

    <!-- Styles -->
    <link href="{{asset('public/css/app.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/css/adminlte.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('public/css/@fontawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    @yield('style')
    <!-- Fonts -->
    {{--<link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" id="app">
        @include('partials.navbar')
        @include('partials.header')
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{asset('public/js/app.js')}}" ></script>
    <script src="{{asset('public/js/adminlte.js')}}" ></script>
    <script>
        let KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };

    </script>
    @yield('scripts');
</body>
</html>
