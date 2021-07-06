<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
@yield('meta')

</head>
<body >
    <div id="app">
            @yield('content')
    </div>

<!-- Scripts -->
    <script src="{{asset('public/js/bootstrap.min.js')}}" ></script>
    <script src="{{asset('public/js/jquery.min.js')}}" ></script>

@yield('scripts');
</body>
</html>
