<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Ultimate Shoe </title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- <script src="{{ asset('js/app.js') }}"></script> -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Montserrat:wght@600&family=Oswald&family=Roboto&family=Train+One&family=Sriracha&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/e3a63f713c.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <!-- <link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet"> -->

        <style>
            #app {
                font-family: 'Montserrat', sans-serif;
            }
            body {
                /* background-color: #FE9D02 !important; */
                background-color: #EEEEEE !important;
            }
        </style>
    </head>
    <body>
        <div id='app'>
            <nav class="navbar navbar-expand-lg navbar-light bg-light @route('dashboard') d-none @endif">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('main') }}">
                        <img src="images/favicon.png" alt="" width="60" height="50">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link @route('main') active-nav @endif" aria-current="page" href="{{ route('main') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @route('comparison') active-nav @endif" href="{{ route('comparison') }}">Comparison</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @route('most-in-demand') active-nav @elseroute('whats-hot') active-nav @endif" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Discover
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('most-in-demand') }}">Most Popular</a></li>
                                <li><a class="dropdown-item" href="{{ route('whats-hot') }}">What's Hot?</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @route('reviews') active-nav @endif" href="{{ route('reviews') }}">Reviews</a>
                        </li>
                    </ul>
                    </div>
                </div>
            </nav>
            <div class="row">
                @yield('content')
            </div>
        </div>
        @vite(['resources/js/app.js'])
        @vite(['resources/sass/app.scss'])
    </body>
</html>
