<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $siteUrl = config('app.url', 'https://vus-bemanning.se');
        $currentUrl = url()->current();
        $pageTitle = isset($pageTitle) ? $pageTitle : (isset($title) ? $title : __('messages.nav.home'));
        $pageDescription = isset($pageDescription) ? $pageDescription : __('messages.about.subtitle');
        $pageImage = isset($pageImage) ? $pageImage : asset('images/logo.png');
    @endphp

    <!-- Primary Meta Tags -->
    <title>@yield('title', 'VUS - ' . $pageTitle)</title>
    <meta name="title" content="@yield('title', 'VUS - ' . $pageTitle)">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="author" content="VUS Bemanning">
    <meta name="keywords" content="rekrytering, bemanning, staffing, recruitment, Sverige, Sweden, jobb, lediga tjänster">
    <link rel="canonical" href="{{ $currentUrl }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:title" content="@yield('title', 'VUS - ' . $pageTitle)">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:site_name" content="VUS Bemanning">
    <meta property="og:locale" content="{{ app()->getLocale() === 'sv' ? 'sv_SE' : 'en_US' }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $currentUrl }}">
    <meta name="twitter:title" content="@yield('title', 'VUS - ' . $pageTitle)">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    <!-- Stylesheets -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/templatemo-leadership-event.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-improvements.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css">
    @stack('styles')

    <!-- Structured Data (JSON-LD) -->
    @php
        $organizationJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'VUS Bemanning',
            'alternateName' => 'VUS',
            'url' => $siteUrl,
            'logo' => asset('images/logo.png'),
            'description' => __('messages.about.subtitle'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'SE',
                'addressLocality' => 'Sweden'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => 'abdulrazek.mahmoud@vus-bemanning.se',
                'contactType' => 'customer service'
            ],
            'sameAs' => []
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $websiteJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'VUS Bemanning',
            'url' => $siteUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $siteUrl . '/jobb?search={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    @endphp
    <script type="application/ld+json">
    {!! $organizationJson !!}
    </script>

    <script type="application/ld+json">
    {!! $websiteJson !!}
    </script>

    @stack('structured-data')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="{{ route('home') }}" class="navbar-brand mx-auto mx-lg-0">
                <span class="brand-text" style="font-size: 28px; font-weight: bold; color: #000000; letter-spacing: 4px;">V U S</span>
            </a>

            @guest
                <div class="d-lg-none">
                    <a class="nav-link btn btn-outline-primary btn-sm me-2" href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>
                    <a class="nav-link custom-btn btn btn-sm" href="{{ route('register') }}">{{ __('messages.nav.register') }}</a>
                </div>
            @else
                <div class="d-lg-none dropdown">
                    <a class="nav-link custom-btn btn btn-sm dropdown-toggle" href="#" id="mobileUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileUserDropdown">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi-speedometer2 me-2"></i>{{ __('messages.nav.profile') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi-gear me-2"></i>{{ __('messages.dashboard.edit_profile') }}</a></li>
                        @if(auth()->user()->is_admin)
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ url('/admin') }}" target="_blank"><i class="bi-shield-check me-2"></i>{{ __('messages.auth.admin_panel') }}</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi-box-arrow-right me-2"></i>{{ __('messages.nav.logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') && !request()->routeIs('about') && !request()->routeIs('company-values') && !request()->routeIs('for-employers') && !request()->routeIs('contact') && !request()->routeIs('jobs.*') && !request()->routeIs('register') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('messages.nav.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('for-employers') ? 'active' : '' }}" href="{{ route('for-employers') }}">{{ __('messages.nav.for_employers') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">{{ __('messages.nav.jobs') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('messages.nav.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('company-values') ? 'active' : '' }}" href="{{ route('company-values') }}">{{ __('messages.nav.company_values') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                    </li>
                    <li class="nav-item me-3">
                        <x-language-switcher />
                    </li>
                    @guest
                        <li class="nav-item me-2">
                            <a class="nav-link btn btn-outline-primary btn-sm d-none d-lg-block" href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-btn btn btn-sm d-none d-lg-block" href="{{ route('register') }}">{{ __('messages.nav.register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link dropdown-toggle d-none d-lg-block" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi-speedometer2 me-2"></i>{{ __('messages.nav.profile') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi-gear me-2"></i>{{ __('messages.dashboard.edit_profile') }}</a></li>
                                @if(auth()->user()->is_admin)
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ url('/admin') }}" target="_blank"><i class="bi-shield-check me-2"></i>{{ __('messages.auth.admin_panel') }}</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="bi-box-arrow-right me-2"></i>{{ __('messages.nav.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
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
                    <span class="brand-text" style="font-size: 28px; font-weight: bold; letter-spacing: 4px; display: block; margin-bottom: 1rem;">V U S</span>
                    <p class="text-white d-flex mb-2">
                        <i class="bi-geo-alt me-2"></i>
                        {{ __('messages.common.country.sweden') }}
                    </p>
                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:abdulrazek.mahmoud@vus-bemanning.se" class="site-footer-link">
                            abdulrazek.mahmoud@vus-bemanning.se
                        </a>
                    </p>
                </div>

                <div class="col-lg-4 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">{{ __('messages.footer.quick_links') }}</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu-item"><a href="{{ route('for-employers') }}" class="footer-menu-link">{{ __('messages.nav.for_employers') }}</a></li>
                        <li class="footer-menu-item"><a href="{{ route('for-employers') }}" class="footer-menu-link">{{ __('messages.nav.free_services') }}</a></li>
                        <li class="footer-menu-item"><a href="{{ route('contact') }}" class="footer-menu-link">{{ __('messages.nav.contact') }}</a></li>
                        <li class="footer-menu-item"><a href="{{ route('privacy') }}" class="footer-menu-link">{{ __('messages.cookie.privacy_policy') }}</a></li>
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
                    <div class="col-lg-6 col-12 text-lg-end">
                        <a href="#" onclick="showCookieSettings(); return false;" class="site-footer-link" style="text-decoration: underline;">{{ __('messages.cookie.settings.title') }}</a>
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
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js"></script>
    @stack('scripts')
</body>
</html>
