<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HALO SULBAR - Suara Sulbar, Untuk Semua | Portal Berita Terkini Sulawesi Barat')</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['site_description'] ?? 'Portal Berita Terkini dan Terpercaya Sulawesi Barat. Menyajikan informasi aktual di 6 kabupaten Sulawesi Barat.')">
    <meta name="keywords" content="@yield('meta_keywords', $siteSettings['site_keywords'] ?? 'halo sulbar, berita sulbar, sulawesi barat, mamuju, polewali mandar, majene, mamasa, pasangkayu, mamuju tengah')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'HALO SULBAR - Suara Sulbar, Untuk Semua')">
    <meta property="og:description" content="@yield('meta_description', $siteSettings['site_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/sulbar_panorama.jpg'))">
    <meta property="og:site_name" content="HALO SULBAR">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'HALO SULBAR - Suara Sulbar, Untuk Semua')">
    <meta name="twitter:description" content="@yield('meta_description', $siteSettings['site_description'] ?? '')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/sulbar_panorama.jpg'))">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Core Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

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
                    <div class="top-info-item">
                        <i class="fas fa-cloud-sun"></i>
                        <span>{{ $siteSettings['weather_label'] ?? '28°C' }}</span>
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
                    <a href="{{ route('home') }}" class="brand-logo-text">
                        <span class="halo">HALO</span>
                        <span class="sulbar">SULBAR</span>
                    </a>
                    <div class="brand-tagline">{{ $siteSettings['site_tagline'] ?? 'Suara Sulbar, Untuk Semua' }}</div>
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
                    <div class="brand-logo-wrap">
                        <a href="{{ route('home') }}" class="brand-logo-text">
                            <span class="halo" style="color: #60A5FA;">HALO</span>
                            <span class="sulbar" style="color: var(--primary-red);">SULBAR</span>
                        </a>
                        <div class="brand-tagline" style="color: #94A3B8;">{{ $siteSettings['site_tagline'] ?? 'Suara Sulbar, Untuk Semua' }}</div>
                    </div>
                    <p class="footer-about-text">
                        {{ $siteSettings['footer_about'] ?? 'HALO SULBAR adalah media siber profesional independen yang menyajikan informasi aktual seputar politik, ekonomi, hukum, pendidikan, wisata dan budaya di Sulawesi Barat.' }}
                    </p>
                    <div class="footer-contact-info" style="font-size:0.8125rem; color:#94A3B8; line-height: 1.6;">
                        <div><i class="fas fa-map-marker-alt" style="color:var(--primary-red); width:18px;"></i> {{ $siteSettings['contact_address'] ?? 'Mamuju, Sulawesi Barat' }}</div>
                        <div><i class="fas fa-envelope" style="color:var(--primary-red); width:18px;"></i> {{ $siteSettings['contact_email'] ?? 'redaksi@halosulbar.com' }}</div>
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
                        Dapatkan pembaruan berita tercepat melalui akun media sosial resmi Halo Sulbar:
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
                        © {{ date('Y') }} <strong>HALO SULBAR</strong>. Suara Sulbar, Untuk Semua. Hak Cipta Dilindungi.
                    </div>
                    <div class="footer-legal-links">
                        <a href="{{ route('page.privasi') }}">Kebijakan Privasi</a>
                        <a href="{{ route('page.syarat') }}">Syarat dan Ketentuan</a>
                        <a href="{{ route('sitemap') }}">Sitemap</a>
                        <a href="{{ route('feed') }}">RSS Feed</a>
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
                <span class="brand-logo-text">
                    <span class="halo" style="font-size: 1.25rem; color:#60A5FA;">HALO</span>
                    <span class="sulbar" style="font-size: 1.6rem; color:var(--primary-red);">SULBAR</span>
                </span>
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
            <li class="mobile-menu-item">
                <a href="{{ route('page.tentang') }}">Tentang Kami</a>
            </li>
            <li class="mobile-menu-item">
                <a href="{{ route('page.redaksi') }}">Redaksi</a>
            </li>
            <li class="mobile-menu-item">
                <a href="{{ route('page.kontak') }}">Kontak & Pasang Iklan</a>
            </li>
        </ul>
    </div>

    {{-- Main Frontend Scripts --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
