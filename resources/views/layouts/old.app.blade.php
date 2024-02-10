<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('css/jquery.datetimepicker.css') }}" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">


</head>


<body style="display:block;height:100vh;">
<div id="app" style="height:100%;display:block">
{{--    @dd(\Request::route()->getName())--}}
    @if(\Request::route()->getName()!="login")
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">


            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="height:40px">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto" style="float:right">
                    <!-- Authentication Links -->
                    @guest



                    @else
                        <li class="nav-item dropdown">

                            @if(Auth::check())
                                <a class="btn btn-primary btn-sm" style="width:80px;height:40px;line-height:14px;border-radius: 10px;font-size: 12px" href="{{ route('logout') }}">
                                    {{ 'LOGOUT'}}
                                </a>
                            @endif


                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @endif


    <div @if(\Request::route()->getName()=="login")  style="width:100%;height:100%;background-image: linear-gradient(rgba(255,255,255,0.3), rgba(255,255,255,0.3)), url({{url('img/background_image_4.jpg')}});
                background-repeat:no-repeat;background-position: center center;background-size: 100%;"  @endif>
        @yield('content')
    </div>
</div>



<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
<script>
    /*jslint browser:true*/
    /*global jQuery, document*/

    jQuery(document).ready(function () {
        'use strict';

        jQuery('#filter-date, #search-from-date, #search-to-date').datetimepicker();
    });
</script>



</body>
</html>
