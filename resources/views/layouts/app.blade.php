<!doctype html>
<html 
    lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    class="text-gray-900 antialiased leading-tight">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Style -->
    <link rel="stylesheet" href={{asset('css/app.css')}}>
    <!-- Style remove unuse dropzone class -->
    <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
</head>
<body class="min-h-screen bg-gray-100">
    <div id="app" class="pl-4 pr-4 flex flex-col h-screen">
        <nav class="nav-bar border-b-2">
            <div class="flex justify-between ">
                <div class="flex items-center">
                    <a href="{{url('/')}}">
                        {{ config('app.name') }}
                    </a>
                </div>
                <div>
                    <!-- Left Side Of Navbar -->
                    @yield('dropzone')
                </div>
                <div>
                    <!-- Right Side Of Navbar -->
                    <ul>
                        <!-- Authentication Links -->
                        @guest
                            <li>
                                <a href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li>
                                    <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li>
                                <a href="{{ route('home') }}">{{ __('Home') }}</a>
                            </li>
                            <li>
                                <a id="navbarDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="h-full">
            @yield('section')
        </main>
    </div>
    @yield('jsFile')
</body>
</html>