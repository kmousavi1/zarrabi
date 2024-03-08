<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Payesh') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{--    <script src="https://cdn.jsdelivr.net/npm/moment@2.27.0"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@0.1.1"></script>--}}
</head>

<body class="min-vh-100">

<div @if(\Request::route()->getName()=="login")
         style="background-image: linear-gradient(rgba(255,255,255,0.3), rgba(255,255,255,0.3)),url({{url('img/background_image_4.jpg')}}); width: 100%; height: 100vh; background-repeat: no-repeat; background-size: cover;"
     @endif
     class="container-fluid">
    @yield('content')
</div>

</body>
</html>
