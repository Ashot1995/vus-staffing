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

    <!-- Google AdSense -->
    <meta name="google-adsense-account" content="ca-pub-7948699933188715">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7948699933188715"
     crossorigin="anonymous"></script>

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
    <!-- Fixed Search Bar -->
    <div class="fixed-search-bar" style="position: fixed; top: 0; left: 0; right: 0; background: #fff; padding: 10px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 1050; display: none;">
        <div class="container">
            <form method="GET" action="{{ route('search') }}" class="d-flex gap-2">
                <input type="text" name="q" id="global-search-input" class="form-control" placeholder="{{ __('messages.search.placeholder') }}" value="{{ request('q', '') }}" autofocus>
                <button type="submit" class="btn custom-btn" style="background: #000; color: #fff; border: none; min-width: 100px;">
                    <i class="bi-search me-1"></i>{{ __('messages.search.button') }}
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="closeSearchBar()" style="min-width: auto;">
                    <i class="bi-x-lg"></i>
                </button>
            </form>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg" id="main-navbar">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="{{ route('home') }}" class="navbar-brand mx-auto mx-lg-0">
                <span class="brand-text" style="font-size: 28px; font-weight: bold; color: #000000; letter-spacing: 4px;">V U S</span>
            </a>
            
            <!-- Search Icon Button -->
            <button type="button" class="btn btn-link text-dark d-lg-none ms-auto me-2" onclick="toggleSearchBar()" style="text-decoration: none; font-size: 1.25rem;">
                <i class="bi-search"></i>
            </button>

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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('about') || request()->routeIs('company-values') || request()->routeIs('blog.*') ? 'active' : '' }}" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.nav.about') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">{{ __('messages.nav.blog') }}</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('company-values') ? 'active' : '' }}" href="{{ route('company-values') }}">{{ __('messages.nav.company_values') }}</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('messages.nav.our_employees') }}</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                    </li>
                    <li class="nav-item me-2">
                        <button type="button" class="btn btn-link text-dark p-0" onclick="toggleSearchBar()" style="text-decoration: none;">
                            <i class="bi-search fs-5"></i>
                        </button>
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

    @php
        $footerSettings = \App\Models\FooterSetting::getActive();
        $locale = app()->getLocale();
    @endphp
    
    <footer class="site-footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-12">
                    <div class="row">
                        <div class="col-12 mb-3" style="flex: 0 0 65%; max-width: 65%;">
                            <span class="brand-text" style="font-size: 22px; font-weight: bold; letter-spacing: 3px; display: block; margin-bottom: 0.5rem;">
                                {{ $footerSettings ? $footerSettings->brand_text : 'V U S' }}
                            </span>
                            <p class="text-white d-flex mb-1" style="margin-bottom: 0.3rem !important;">
                                <i class="bi-geo-alt me-2"></i>
                                {{ $footerSettings ? ($locale === 'sv' ? $footerSettings->location_sv : $footerSettings->location_en) : __('messages.common.country.sweden') }}
                            </p>
                            <p class="text-white d-flex mb-0">
                                <i class="bi-envelope me-2"></i>
                                <a href="mailto:{{ $footerSettings ? $footerSettings->email : 'abdulrazek.mahmoud@vus-bemanning.se' }}" class="site-footer-link">
                                    {{ $footerSettings ? $footerSettings->email : 'abdulrazek.mahmoud@vus-bemanning.se' }}
                                </a>
                            </p>
                        </div>

                        <div class="col-12 mb-3 quick-links-section">
                            <h5 class="site-footer-title mb-2">
                                {{ $footerSettings ? ($locale === 'sv' ? $footerSettings->quick_links_title_sv : $footerSettings->quick_links_title_en) : __('messages.footer.quick_links') }}
                            </h5>
                            <ul class="footer-menu">
                                @if($footerSettings && !empty($footerSettings->quick_links))
                                    @foreach($footerSettings->quick_links as $link)
                                        @php
                                            $label = $locale === 'sv' ? ($link['label_sv'] ?? $link['label_en'] ?? '') : ($link['label_en'] ?? '');
                                            $url = !empty($link['route']) ? route($link['route']) : ($link['custom_url'] ?? '#');
                                        @endphp
                                        <li class="footer-menu-item">
                                            <a href="{{ $url }}" class="footer-menu-link">{{ $label }}</a>
                                        </li>
                                    @endforeach
                                @else
                                    {{-- Default links if no settings --}}
                                    <li class="footer-menu-item"><a href="{{ route('for-employers') }}" class="footer-menu-link">{{ __('messages.nav.for_employers') }}</a></li>
                                    <li class="footer-menu-item"><a href="{{ route('for-employers') }}" class="footer-menu-link">{{ __('messages.nav.free_services') }}</a></li>
                                    <li class="footer-menu-item"><a href="{{ route('contact') }}" class="footer-menu-link">{{ __('messages.nav.contact') }}</a></li>
                                    <li class="footer-menu-item"><a href="{{ route('privacy') }}" class="footer-menu-link">{{ __('messages.cookie.privacy_policy') }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-12">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <p class="copyright-text mb-0">
                                    @if($footerSettings && ($footerSettings->copyright_en || $footerSettings->copyright_sv))
                                        @php
                                            $copyright = $locale === 'sv' ? ($footerSettings->copyright_sv ?: $footerSettings->copyright_en) : ($footerSettings->copyright_en ?: $footerSettings->copyright_sv);
                                            $copyright = str_replace(':year', date('Y'), $copyright);
                                            $copyright = str_replace('2025', date('Y'), $copyright);
                                        @endphp
                                        {{ $copyright }}
                                    @else
                                        {{ __('messages.footer.copyright', ['year' => date('Y')]) }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-lg-6 col-12 text-lg-end">
                                <a href="#" onclick="showCookieSettings(); return false;" class="site-footer-link" style="text-decoration: underline;">{{ __('messages.cookie.settings.title') }}</a>
                            </div>
                        </div>
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
    
    <script>
        function toggleSearchBar() {
            const searchBar = document.querySelector('.fixed-search-bar');
            const navbar = document.getElementById('main-navbar');
            
            if (searchBar.style.display === 'none' || !searchBar.style.display) {
                searchBar.style.display = 'block';
                searchBar.classList.add('active');
                if (navbar) {
                    navbar.style.marginTop = '60px';
                }
                setTimeout(() => {
                    const input = document.getElementById('global-search-input');
                    if (input) input.focus();
                }, 100);
            } else {
                searchBar.style.display = 'none';
                searchBar.classList.remove('active');
                if (navbar) {
                    navbar.style.marginTop = '';
                }
            }
        }
        
        function closeSearchBar() {
            const searchBar = document.querySelector('.fixed-search-bar');
            const navbar = document.getElementById('main-navbar');
            
            searchBar.style.display = 'none';
            searchBar.classList.remove('active');
            if (navbar) {
                navbar.style.marginTop = '';
            }
        }
        
        // Close search bar on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const searchBar = document.querySelector('.fixed-search-bar');
                if (searchBar && searchBar.style.display !== 'none') {
                    closeSearchBar();
                }
            }
        });
        
        // Highlight search terms if search parameter exists
        @if(request()->has('search'))
        document.addEventListener('DOMContentLoaded', function() {
            const searchTerm = '{{ request('search') }}';
            highlightSearchTerm(searchTerm);
        });
        @endif
        
        function highlightSearchTerm(term) {
            if (!term) return;
            
            const escapedTerm = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escapedTerm})`, 'gi');
            
            // Find all text nodes in the main content area
            const mainContent = document.querySelector('main') || document.body;
            const walker = document.createTreeWalker(
                mainContent,
                NodeFilter.SHOW_TEXT,
                {
                    acceptNode: function(node) {
                        // Skip script, style, and nav elements
                        const parent = node.parentElement;
                        if (!parent) return NodeFilter.FILTER_REJECT;
                        
                        const tagName = parent.tagName;
                        if (tagName === 'SCRIPT' || tagName === 'STYLE' || 
                            tagName === 'NAV' || tagName === 'FOOTER' ||
                            parent.closest('nav') || parent.closest('footer')) {
                            return NodeFilter.FILTER_REJECT;
                        }
                        
                        if (node.textContent.trim().length === 0) {
                            return NodeFilter.FILTER_REJECT;
                        }
                        
                        return NodeFilter.FILTER_ACCEPT;
                    }
                },
                false
            );
            
            const textNodes = [];
            let node;
            while (node = walker.nextNode()) {
                textNodes.push(node);
            }
            
            textNodes.forEach(textNode => {
                const originalText = textNode.textContent;
                if (regex.test(originalText)) {
                    const highlighted = originalText.replace(regex, '<mark class="search-highlight">$1</mark>');
                    if (highlighted !== originalText) {
                        const wrapper = document.createElement('span');
                        wrapper.innerHTML = highlighted;
                        textNode.parentNode.replaceChild(wrapper, textNode);
                    }
                }
            });
            
            // Scroll to first highlight after a short delay
            setTimeout(() => {
                const firstMark = document.querySelector('mark.search-highlight');
                if (firstMark) {
                    firstMark.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        }
    </script>
    
    @stack('scripts')
</body>
</html>
