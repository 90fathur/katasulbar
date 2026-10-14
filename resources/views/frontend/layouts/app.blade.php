<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KATA SULBAR - Suara Sulbar, Untuk Semua | Portal Berita Terkini Sulawesi Barat')</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['site_description'] ?? 'Portal Berita Terkini dan Terpercaya Sulawesi Barat. Menyajikan informasi aktual di 6 kabupaten Sulawesi Barat.')">
    <meta name="keywords" content="@yield('meta_keywords', $siteSettings['site_keywords'] ?? 'kata sulbar, berita sulbar, sulawesi barat, mamuju, polewali mandar, majene, mamasa, pasangkayu, mamuju tengah')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'KATA SULBAR - Suara Sulbar, Untuk Semua')">
    <meta property="og:description" content="@yield('meta_description', $siteSettings['site_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/sulbar_panorama.jpg'))">
    <meta property="og:site_name" content="KATA SULBAR">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'KATA SULBAR - Suara Sulbar, Untuk Semua')">
    <meta name="twitter:description" content="@yield('meta_description', $siteSettings['site_description'] ?? '')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/sulbar_panorama.jpg'))">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-transparent.png') }}">

    {{-- Core Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ file_exists(public_path('assets/css/style.css')) ? filemtime(public_path('assets/css/style.css')) : time() }}">

    {{-- Schema.org NewsArticle or WebSite --}}
    @yield('schema_markup')

    @stack('styles')
</head>
<body>

    {{-- 1. TOP BAR --}}
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="top-bar-left">
                    <div class="top-info-item">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <span class="top-info-divider">•</span>
                    @php
                        $weather = $currentWeather ?? \App\Services\WeatherService::getSulbarWeather();
                    @endphp
                    <div class="top-info-item weather-widget" id="weatherWidget" tabindex="0" title="Klik / Arahkan kursor untuk melihat cuaca 6 kabupaten di Sulawesi Barat">
                        <i class="{{ $weather['icon'] ?? 'fas fa-cloud-sun' }}" style="color: {{ $weather['color'] ?? '#38BDF8' }}; font-size: 0.85rem;"></i>
                        <span class="weather-temp-label" style="font-weight: 700;">{{ $weather['temp_label'] ?? '29°C' }}</span>
                        <span class="weather-cond-label" style="opacity: 0.85; font-size: 0.72rem;">{{ $weather['condition'] ?? 'Cerah Berawan' }}</span>
                        <i class="fas fa-chevron-down weather-chevron" style="font-size: 0.55rem; margin-left: 2px; opacity: 0.7;"></i>

                        {{-- Realtime weather dropdown for 6 regencies in Sulbar --}}
                        <div class="weather-popup">
                            <div class="weather-popup-header">
                                <div class="weather-popup-title">
                                    <i class="fas fa-temperature-high" style="color:var(--primary-red)"></i>
                                    <strong>Cuaca Sulawesi Barat (Realtime)</strong>
                                </div>
                                <span class="weather-popup-badge">{{ $weather['updated_at'] ?? 'Hari ini' }}</span>
                            </div>

                            <div class="weather-grid">
                                @foreach(($weather['regencies'] ?? []) as $reg)
                                    <div class="weather-city-card {{ ($reg['is_capital'] ?? false) ? 'is-capital' : '' }}">
                                        <div class="weather-city-top">
                                            <span class="weather-city-name">
                                                {{ $reg['name'] }}
                                                @if($reg['is_capital'] ?? false)
                                                    <span style="font-size: 0.55rem; color: var(--primary-red); font-weight: 600;">(Ibukota)</span>
                                                @endif
                                            </span>
                                            <i class="{{ $reg['icon'] }}" style="color: {{ $reg['color'] }}; font-size: 0.8rem;"></i>
                                        </div>
                                        <div class="weather-city-bottom">
                                            <span class="weather-city-temp">{{ $reg['temp_label'] }}</span>
                                            <span class="weather-city-desc">{{ $reg['condition'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="weather-popup-footer">
                                <span><i class="fas fa-satellite-dish" style="color:#10B981"></i> Data Satelit BMKG & Open-Meteo • Realtime</span>
                            </div>
                        </div>
                    </div>
                    <span class="top-info-divider">•</span>
                    <div class="top-info-item location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $siteSettings['location_label'] ?? 'Mamuju, Sulawesi Barat' }}</span>
                    </div>
                </div>

                <div class="top-bar-right">
                    <nav class="top-nav-links">
                        <a href="{{ route('page.tentang') }}">Tentang Kami</a>
                        <a href="{{ route('page.redaksi') }}">Redaksi</a>
                        <a href="{{ route('page.kontak') }}">Kontak</a>
                    </nav>

                    <div class="top-social-links">
                        <a href="{{ $siteSettings['facebook_url'] ?? '#' }}" target="_blank" rel="noopener" class="top-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $siteSettings['instagram_url'] ?? '#' }}" target="_blank" rel="noopener" class="top-social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $siteSettings['youtube_url'] ?? '#' }}" target="_blank" rel="noopener" class="top-social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $siteSettings['tiktok_url'] ?? '#' }}" target="_blank" rel="noopener" class="top-social-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>

                    <button class="theme-toggle-btn" id="themeToggleBtn" title="Ganti Tema">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. BRANDING HEADER --}}
    <header class="branding-header">
        <div class="branding-header-bg"></div>
        <div class="container">
            <div class="branding-header-inner">
                <div class="brand-logo-wrap">
                    <a href="{{ route('home') }}" class="brand-logo-img-link" title="KATA SULBAR - {{ $siteSettings['site_tagline'] ?? 'Suara Sulbar, Untuk Semua' }}">
                        <img src="{{ asset('assets/images/logo-transparent.png') }}" alt="KATA SULBAR - Suara Sulbar, Untuk Semua" class="main-site-logo logo-light">
                        <img src="{{ asset('assets/images/logo-dark-bg.png') }}" alt="KATA SULBAR - Suara Sulbar, Untuk Semua" class="main-site-logo logo-dark">
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. NAVBAR (STICKY RESPONSIVE) --}}
    <nav class="main-navbar">
        <div class="container">
            <div class="navbar-inner">
                <div class="nav-left-group">
                    <button class="nav-mobile-toggle" aria-label="Menu Navigasi">
                        <i class="fas fa-bars"></i>
                    </button>

                    <a href="{{ route('home') }}" class="nav-home-btn" title="Beranda">
                        <i class="fas fa-home"></i>
                    </a>

                    <ul class="nav-menu-list">
                        @if(isset($navMenus) && $navMenus->count() > 0)
                            @foreach($navMenus as $menu)
                                @php
                                    $hasChildren = $menu->activeChildren->isNotEmpty();
                                    $currentUrl = url()->current();
                                    $isActive = ($menu->final_url !== '#' && $currentUrl === $menu->final_url) ||
                                                ($menu->type === 'category' && $menu->category && request()->is('kategori/' . $menu->category->slug . '*')) ||
                                                ($menu->url && $menu->url !== '/' && $menu->url !== '#' && request()->is(trim($menu->url, '/') . '*'));
                                    if (!$isActive && $hasChildren) {
                                        foreach($menu->activeChildren as $c) {
                                            if (($c->final_url !== '#' && $currentUrl === $c->final_url) ||
                                                ($c->type === 'category' && $c->category && request()->is('kategori/' . $c->category->slug . '*')) ||
                                                ($c->url && $c->url !== '/' && $c->url !== '#' && request()->is(trim($c->url, '/') . '*'))) {
                                                $isActive = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <li class="nav-item {{ $hasChildren ? 'has-dropdown' : '' }} {{ $isActive ? 'active' : '' }}">
                                    <a href="{{ $menu->final_url }}" class="nav-link" target="{{ $menu->target ?: '_self' }}">
                                        @if($menu->icon)
                                            <i class="{{ $menu->icon }}" style="font-size:0.8rem; margin-right:2px;"></i>
                                        @endif
                                        <span>{{ $menu->title }}</span>
                                        @if($menu->badge)
                                            <span class="nav-badge-video" style="background-color: {{ $menu->badge_color ?: 'var(--primary-red)' }}">{{ $menu->badge }}</span>
                                        @endif
                                        @if($hasChildren)
                                            <i class="fas fa-chevron-down nav-dropdown-arrow" style="font-size:0.65rem; margin-left: 3px;"></i>
                                        @endif
                                    </a>

                                    @if($hasChildren)
                                        <div class="nav-dropdown">
                                            @foreach($menu->activeChildren as $child)
                                                <a href="{{ $child->final_url }}" class="nav-dropdown-link" target="{{ $child->target ?: '_self' }}">
                                                    <span class="nav-dropdown-title">
                                                        @if($child->icon)
                                                            <i class="{{ $child->icon }}" style="margin-right: 6px; font-size: 0.8rem; opacity: 0.85;"></i>
                                                        @endif
                                                        {{ $child->title }}
                                                    </span>
                                                    @if($child->badge)
                                                        <span class="nav-badge-video" style="background-color: {{ $child->badge_color ?: 'var(--primary-red)' }}; font-size: 0.55rem; padding: 0.1rem 0.35rem; margin-left: auto;">{{ $child->badge }}</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                            </li>

                            @foreach($navCategories as $cat)
                                <li class="nav-item {{ request()->is('kategori/' . $cat->slug) ? 'active' : '' }}">
                                    <a href="{{ route('category.show', $cat->slug) }}" class="nav-link">
                                        {{ $cat->name }}
                                        @if($cat->children && $cat->children->count())
                                            <i class="fas fa-chevron-down" style="font-size:0.65rem"></i>
                                        @endif
                                    </a>

                                    @if($cat->children && $cat->children->count())
                                        <div class="nav-dropdown">
                                            @foreach($cat->children as $sub)
                                                <a href="{{ route('category.show', $sub->slug) }}" class="nav-dropdown-link">{{ $sub->name }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach

                            {{-- Menu Video (Section 31) --}}
                            <li class="nav-item {{ request()->is('video*') ? 'active' : '' }}">
                                <a href="{{ route('video.index') }}" class="nav-link">
                                    Video <span class="nav-badge-video">NEW</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                {{-- Nav Search Box --}}
                <div class="nav-search-wrap">
                    <form action="{{ route('news.search') }}" method="GET" class="nav-search-form">
                        <i class="fas fa-search nav-search-icon-left"></i>
                        <input type="text" name="q" class="nav-search-input" placeholder="Cari berita..." value="{{ request('q') }}" autocomplete="off">
                        <button type="submit" class="nav-search-btn" title="Cari">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- 4. BREAKING NEWS TICKER --}}
    @if(isset($breakingNews) && $breakingNews->count() > 0)
        <div class="breaking-news-bar">
            <div class="container">
                <div class="breaking-news-inner">
                    <div class="breaking-badge">
                        <i class="fas fa-bolt" style="margin-right: 0.35rem"></i> BERITA TERKINI
                    </div>
                    <div class="breaking-ticker-wrap">
                        <div class="breaking-ticker-content" id="breakingTicker">
                            @foreach($breakingNews as $item)
                                <a href="{{ $item->target_url }}" class="ticker-item">
                                    <span class="ticker-bullet"></span> {{ $item->title }}
                                </a>
                            @endforeach
                            {{-- Repeat for seamless continuous scrolling animation --}}
                            @foreach($breakingNews as $item)
                                <a href="{{ $item->target_url }}" class="ticker-item">
                                    <span class="ticker-bullet"></span> {{ $item->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="ticker-controls">
                        <button class="ticker-ctrl-btn" onclick="document.getElementById('breakingTicker').style.animationPlayState = 'paused'" title="Pause"><i class="fas fa-pause"></i></button>
                        <button class="ticker-ctrl-btn" onclick="document.getElementById('breakingTicker').style.animationPlayState = 'running'" title="Play"><i class="fas fa-play"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MAIN CONTENT INJECTION --}}
    <main class="main-content-wrap">
        @yield('content')
    </main>

    {{-- 13. FOOTER --}}
    <footer class="main-footer">
        <div class="container">
            <div class="footer-inner-grid">
                {{-- Col 1: Brand & About --}}
                <div class="footer-col">
                    <div class="brand-logo-wrap" style="margin-bottom: 0.85rem;">
                        <a href="{{ route('home') }}" class="brand-logo-img-link">
                            <img src="{{ asset('assets/images/logo-dark-bg.png') }}" alt="KATA SULBAR - Suara Sulbar, Untuk Semua" style="height: 52px; width: auto; object-fit: contain;">
                        </a>
                    </div>
                    <p class="footer-about-text">
                        {{ $siteSettings['footer_about'] ?? 'KATA SULBAR adalah media siber profesional independen yang menyajikan informasi aktual seputar politik, ekonomi, hukum, pendidikan, wisata dan budaya di Sulawesi Barat.' }}
                    </p>
                    <div class="footer-contact-info" style="font-size:0.8125rem; color:#94A3B8; line-height: 1.6;">
                        <div><i class="fas fa-map-marker-alt" style="color:var(--primary-red); width:18px;"></i> {{ $siteSettings['contact_address'] ?? 'Mamuju, Sulawesi Barat' }}</div>
                        <div><i class="fas fa-envelope" style="color:var(--primary-red); width:18px;"></i> {{ $siteSettings['contact_email'] ?? 'redaksi@katasulbar.com' }}</div>
                        <div><i class="fas fa-phone" style="color:var(--primary-red); width:18px;"></i> {{ $siteSettings['contact_phone'] ?? '+62 811-4200-8899' }}</div>
                    </div>
                </div>

                {{-- Col 2: Navigation --}}
                <div class="footer-col">
                    <h4 class="footer-col-title">Navigasi</h4>
                    <ul class="footer-nav-list">
                        <li><a href="{{ route('home') }}"><i class="fas fa-angle-right"></i> Beranda</a></li>
                        <li><a href="{{ route('page.tentang') }}"><i class="fas fa-angle-right"></i> Tentang Kami</a></li>
                        <li><a href="{{ route('page.redaksi') }}"><i class="fas fa-angle-right"></i> Redaksi</a></li>
                        <li><a href="{{ route('page.pedoman') }}"><i class="fas fa-angle-right"></i> Pedoman Media Siber</a></li>
                        <li><a href="{{ route('page.kontak') }}"><i class="fas fa-angle-right"></i> Kontak & Iklan</a></li>
                        <li><a href="{{ route('video.index') }}"><i class="fas fa-angle-right"></i> Galeri Video</a></li>
                    </ul>
                </div>

                {{-- Col 3: Categories --}}
                <div class="footer-col">
                    <h4 class="footer-col-title">Kategori</h4>
                    <div class="footer-categories-grid">
                        @foreach($navCategories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}"><i class="fas fa-chevron-right" style="font-size:0.65rem; color:var(--primary-red)"></i> {{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- Col 4: Social Media --}}
                <div class="footer-col">
                    <h4 class="footer-col-title">Ikuti Kami</h4>
                    <p style="font-size:0.8125rem; color:#94A3B8; margin-bottom: 0.75rem;">
                        Dapatkan pembaruan berita tercepat melalui akun media sosial resmi Kata Sulbar:
                    </p>
                    <div class="footer-social-wrap">
                        <a href="{{ $siteSettings['facebook_url'] ?? '#' }}" target="_blank" rel="noopener" class="footer-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $siteSettings['instagram_url'] ?? '#' }}" target="_blank" rel="noopener" class="footer-social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $siteSettings['youtube_url'] ?? '#' }}" target="_blank" rel="noopener" class="footer-social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $siteSettings['tiktok_url'] ?? '#' }}" target="_blank" rel="noopener" class="footer-social-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="{{ $siteSettings['whatsapp_channel'] ?? '#' }}" target="_blank" rel="noopener" class="footer-social-btn" title="WhatsApp Channel"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            {{-- Bottom copyright bar --}}
            <div class="footer-bottom-bar">
                <div class="footer-bottom-inner">
                    <div>
                        © {{ date('Y') }} <strong>KATA SULBAR</strong>. Suara Sulbar, Untuk Semua. Hak Cipta Dilindungi.
                    </div>
                    <div class="footer-legal-links">
                        <a href="{{ route('page.privasi') }}">Kebijakan Privasi</a>
                        <a href="{{ route('page.syarat') }}">Syarat dan Ketentuan</a>
                        <a href="{{ route('sitemap') }}">Sitemap</a>
                        <a href="{{ route('feed') }}">RSS Feed</a>
                        <a href="{{ route('admin.login') }}" style="color: #CBD5E1;"><i class="fas fa-lock" style="font-size:0.7rem; color:var(--primary-red)"></i> Masuk Redaksi</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Off-canvas Drawer Navigation --}}
    <div class="mobile-drawer-backdrop"></div>
    <div class="mobile-drawer">
        <div class="mobile-drawer-header">
            <div class="brand-logo-wrap">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo-dark-bg.png') }}" alt="KATA SULBAR" style="height: 44px; width: auto; object-fit: contain;">
                </a>
            </div>
            <button class="mobile-drawer-close"><i class="fas fa-times"></i></button>
        </div>

        <form action="{{ route('news.search') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="position: relative;">
                <input type="text" name="q" class="comment-input" placeholder="Cari berita..." style="background: rgba(255,255,255,0.1); color:#fff; border: 1px solid rgba(255,255,255,0.2);">
                <button type="submit" style="position: absolute; right: 8px; top: 10px; background: none; border: none; color: #fff;"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <ul class="mobile-menu-list">
            @if(isset($navMenus) && $navMenus->count() > 0)
                @foreach($navMenus as $menu)
                    @php
                        $hasChildren = $menu->activeChildren->isNotEmpty();
                        $currentUrl = url()->current();
                        $isActive = ($menu->final_url !== '#' && $currentUrl === $menu->final_url) ||
                                    ($menu->type === 'category' && $menu->category && request()->is('kategori/' . $menu->category->slug . '*')) ||
                                    ($menu->url && $menu->url !== '/' && $menu->url !== '#' && request()->is(trim($menu->url, '/') . '*'));
                        if (!$isActive && $hasChildren) {
                            foreach($menu->activeChildren as $c) {
                                if (($c->final_url !== '#' && $currentUrl === $c->final_url) ||
                                    ($c->type === 'category' && $c->category && request()->is('kategori/' . $c->category->slug . '*')) ||
                                    ($c->url && $c->url !== '/' && $c->url !== '#' && request()->is(trim($c->url, '/') . '*'))) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <li class="mobile-menu-item {{ $hasChildren ? 'has-dropdown' : '' }} {{ $isActive ? 'active' : '' }}">
                        <div class="mobile-menu-link-row">
                            <a href="{{ $menu->final_url }}" target="{{ $menu->target ?: '_self' }}" class="mobile-menu-link">
                                @if($menu->icon)
                                    <i class="{{ $menu->icon }}" style="margin-right: 0.5rem"></i>
                                @endif
                                <span>{{ $menu->title }}</span>
                                @if($menu->badge)
                                    <span class="nav-badge-video" style="background-color: {{ $menu->badge_color ?: 'var(--primary-red)' }}; font-size: 0.55rem; padding: 0.1rem 0.35rem; margin-left: 0.35rem;">{{ $menu->badge }}</span>
                                @endif
                            </a>
                            @if($hasChildren)
                                <button type="button" class="mobile-submenu-toggle" aria-label="Toggle {{ $menu->title }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            @endif
                        </div>
                        @if($hasChildren)
                            <ul class="mobile-submenu-list">
                                @foreach($menu->activeChildren as $child)
                                    @php
                                        $childActive = ($child->final_url !== '#' && url()->current() === $child->final_url) ||
                                                       ($child->type === 'category' && $child->category && request()->is('kategori/' . $child->category->slug . '*'));
                                    @endphp
                                    <li class="mobile-submenu-item {{ $childActive ? 'active' : '' }}">
                                        <a href="{{ $child->final_url }}" target="{{ $child->target ?: '_self' }}">
                                            @if($child->icon)
                                                <i class="{{ $child->icon }}" style="margin-right: 0.5rem; opacity: 0.7;"></i>
                                            @else
                                                <i class="fas fa-angle-right" style="margin-right: 0.4rem; opacity: 0.5; font-size: 0.75rem;"></i>
                                            @endif
                                            <span>{{ $child->title }}</span>
                                            @if($child->badge)
                                                <span class="nav-badge-video" style="background-color: {{ $child->badge_color ?: 'var(--primary-red)' }}; font-size: 0.5rem; padding: 0.05rem 0.3rem; margin-left: auto;">{{ $child->badge }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @else
                <li class="mobile-menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}"><i class="fas fa-home" style="margin-right: 0.5rem"></i> Beranda</a>
                </li>
                @foreach($navCategories as $cat)
                    <li class="mobile-menu-item {{ request()->is('kategori/' . $cat->slug) ? 'active' : '' }}">
                        <a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a>
                    </li>
                @endforeach
                <li class="mobile-menu-item {{ request()->is('video*') ? 'active' : '' }}">
                    <a href="{{ route('video.index') }}"><i class="fas fa-play-circle" style="color:var(--primary-red); margin-right: 0.5rem"></i> Video Berita</a>
                </li>
            @endif
            <li class="mobile-menu-divider" style="height: 1px; background: rgba(255,255,255,0.1); margin: 0.75rem 0;"></li>
            <li class="mobile-menu-item">
                <a href="{{ route('page.tentang') }}"><i class="fas fa-info-circle" style="margin-right: 0.5rem"></i> Tentang Kami</a>
            </li>
            <li class="mobile-menu-item">
                <a href="{{ route('page.redaksi') }}"><i class="fas fa-users" style="margin-right: 0.5rem"></i> Redaksi</a>
            </li>
            <li class="mobile-menu-item">
                <a href="{{ route('page.kontak') }}"><i class="fas fa-envelope" style="margin-right: 0.5rem"></i> Kontak & Pasang Iklan</a>
            </li>
        </ul>
    </div>

    {{-- Main Frontend Scripts --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
