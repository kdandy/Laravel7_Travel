@php
    $company = App\Company::find(1);
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-name" content="{{ config('app.name') }}">
    <meta name="base-url" content="{{ url('') }}">
    <meta name="latitude" content="{{ $company->latitude }}">
    <meta name="logo" content="{{ asset(Storage::url($company->logo)) }}">
    <meta name="longitude" content="{{ $company->longitude }}">

    <title>@yield('title') - {{ $company->name }}</title>
    <link rel="shortcut icon" href="{{ asset(Storage::url($company->logo)) }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="{{ asset('@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    {{-- <script src="https://kit.fontawesome.com/5731b34870.js" crossorigin="anonymous"></script> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <style>
        html, body {
            scroll-behavior: smooth;
            height: 100%;
            margin: 0;
        }

        .content {
            padding: 20px;
            min-height: 100%;
            margin: 0 auto -50px;
        }

        .push {
            height: 50px;
        }
    </style>
    @yield('styles')
</head>

<body style="background-color:#1c1e21">
    @include('sweet::alert')
    <div id="app" class="text-white content">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm font-weight-bold fixed-top">
            <div class="container">
                <a class="navbar-brand text-monospace" href="{{ url('/') }}">
                    {{ $company->name }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ url('') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ url('/#tour-package') }}">{{ __('Tour Package') }}</a>
                        </li>
                        @if(App\Gallery::where('is_portofolio', true)->count() > 2)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/#portofolio') }}">{{ __('Portofolio') }}</a>
                            </li>
                        @endif
                        @if(App\Order::whereRating(5)->count() > 2)
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ url('/#testimonial') }}">{{ __('Testimonial') }}</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ url('/#contact') }}">{{ __('Contact') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ url('/#about') }}">{{ __('About') }}</a>
                        </li>
                        @can('admin')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex-inline" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Menu
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item {{ Request::segment(1) == "user" ? "active" : "" }}"    href="{{ route('user.index') }}">{{ __('Users') }}</a>
                                    <a class="dropdown-item {{ Request::segment(1) == "company" ? "active" : "" }}" href="{{ route('company.index') }}">{{ __('Company') }}</a>
                                    <a class="dropdown-item {{ Request::segment(1) == "gallery" ? "active" : "" }}" href="{{ route('gallery.index') }}">{{ __('Gallery') }}</a>
                                    <a class="dropdown-item {{ Request::segment(1) == "package" ? "active" : "" }}" href="{{ route('package.index') }}">{{ __('Package') }}</a>
                                    <a class="dropdown-item {{ Request::segment(1) == "tour" ? "active" : "" }}"    href="{{ route('tour.index') }}">{{ __('Tour') }}</a>
                                    <a class="dropdown-item {{ Request::segment(1) == "order" ? "active" : "" }}"   href="{{ route('order.index') }}">{{ __('Transaction') }}</a>
                                </div>
                            </li>
                        @endcan

                        @can('member')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('order.index') }}">{{ __('Transaction') }}</a>
                            </li>
                        @endcan

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item {{ Request::segment(1) == "login" ? "active" : "" }}">
                                <a class="nav-link"
                                    href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item {{ Request::segment(1) == "register" ? "active" : "" }}">
                                    <a class="nav-link"
                                        href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex-inline" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img id="img-avatar-header" src="{{ asset(Storage::url(auth()->user()->avatar)) }}" class="rounded-circle" width="32" height="32" alt="Frontted">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item {{ Request::segment(1) == "profil" ? "active" : "" }}" href="{{ route('profile') }}">{{ __('Profile') }}</a>
                                    <a class="dropdown-item {{ Request::segment(1) == "pengaturan" ? "active" : "" }}" href="{{ route('setting') }}">{{ __('Setting') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-fw fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main style="margin-top: 100px" class="mb-5">
            @yield('content')
        </main>
        <div class="push"></div>
    </div>
    <footer class="bg-dark py-3 text-white footer">
        <div class="container text-center">
            @if ($company->instagram && $company->youtube)
                <div class="mb-3">
                    <a href="{{ $company->instagram }}" title="Instagram" class="btn btn-secondary rounded-circle"><i class="fab fa-instagram fa-2x"></i></a>
                    <a href="{{ $company->youtube }}" title="Youtube" class="btn btn-secondary rounded-circle"><i class="fab fa-youtube fa-2x"></i></a>
                </div>
            @endif
            <div>Copyright &copy; {{ $company->name }} {{ now()->year }}</div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        const baseURL = $("meta[name='base-url']").attr('content');
        const _token = $("meta[name='csrf-token']").attr('content');
        setTimeout(() => { console.clear(); }, 100);
    </script>

    @stack('scripts')
</body>

</html>
