<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VUS - Din partner för rekrytering och bemanning">
    <meta name="author" content="VUS">
    <title>@yield('title', 'VUS')</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/templatemo-leadership-event.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-improvements.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="{{ route('home') }}" class="navbar-brand mx-auto mx-lg-0">
                <i class="bi-briefcase brand-logo"></i>
                <span class="brand-text">VUS</span>
            </a>

            @guest
                <a class="nav-link custom-btn btn d-lg-none" href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>
            @else
                <a class="nav-link custom-btn btn d-lg-none" href="{{ route('dashboard') }}">{{ __('messages.nav.profile') }}</a>
            @endguest

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') && !request()->routeIs('about') && !request()->routeIs('for-employers') && !request()->routeIs('contact') && !request()->routeIs('jobs.*') && !request()->routeIs('register') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('messages.nav.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('for-employers') ? 'active' : '' }}" href="{{ route('for-employers') }}">{{ __('messages.nav.for_employers') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">{{ __('messages.nav.jobs') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">{{ __('messages.nav.for_job_seekers') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                    </li>
                    <li class="nav-item me-3">
                        <x-language-switcher />
                    </li>
                    @guest
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link custom-btn btn d-none d-lg-block" href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>--}}
{{--                        </li>--}}
                    @else
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link custom-btn btn d-none d-lg-block" href="{{ route('dashboard') }}">{{ __('messages.nav.profile') }}</a>--}}
{{--                        </li>--}}
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <x-cookie-banner />

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">VUS</h5>
                    <p class="text-white d-flex mb-2">
                        <i class="bi-geo-alt me-2"></i>
                        {{ app()->getLocale() === 'en' ? 'Sweden' : (app()->getLocale() === 'de' ? 'Schweiz' : 'Sverige') }}
                    </p>
                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:info@vus-bemanning.se" class="site-footer-link">
                            info@vus-bemanning.se
                        </a>
                    </p>
                </div>

                <div class="col-lg-4 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">{{ __('messages.footer.quick_links') }}</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu-item"><a href="{{ route('for-employers') }}" class="footer-menu-link">{{ __('messages.nav.for_employers') }}</a></li>
                        <li class="footer-menu-item"><a href="{{ route('for-employers') }}" class="footer-menu-link">{{ __('messages.nav.free_services') }}</a></li>
                        <li class="footer-menu-item"><a href="{{ route('contact') }}" class="footer-menu-link">{{ __('messages.nav.contact') }}</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-12">
                    <h5 class="site-footer-title mb-3">{{ __('messages.footer.follow_us') }}</h5>
                    <ul class="social-icon">
                        <li><a href="#" class="social-icon-link bi-linkedin"></a></li>
                        <li><a href="#" class="social-icon-link bi-facebook"></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <p class="copyright-text mb-0">{{ __('messages.footer.copyright', ['year' => date('Y')]) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/click-scroll.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>
