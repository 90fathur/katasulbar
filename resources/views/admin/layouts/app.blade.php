<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - KATA SULBAR')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : (!empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('assets/images/logo-transparent.png')) }}">

    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v={{ file_exists(public_path('assets/css/admin.css')) ? filemtime(public_path('assets/css/admin.css')) : time() }}">

    @stack('styles')
</head>
<body>

    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

    <div class="admin-wrapper">
        {{-- SIDEBAR --}}
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <img src="{{ !empty($siteSettings['site_logo_dark']) ? $siteSettings['site_logo_dark'] : (!empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('assets/images/logo-dark-bg.png')) }}" alt="{{ $siteSettings['site_name'] ?? 'KATA SULBAR' }}" style="height: 26px; width: auto; object-fit: contain;">
                </a>
                <div style="display: flex; align-items: center; gap: 0.4rem;">
                    <span class="admin-badge-panel">ADMIN</span>
                    <button class="admin-sidebar-close" id="adminSidebarClose" aria-label="Tutup Menu" style="background:none; border:none; color:#CBD5E1; font-size:1rem; cursor:pointer; padding: 0.2rem;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <nav class="admin-sidebar-nav">
                <div class="admin-nav-category">Utama</div>
                <div class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>

                <div class="admin-nav-category">Konten Editorial</div>
                <div class="admin-nav-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.articles.index') }}">
                        <i class="fas fa-newspaper"></i> Berita & Artikel
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-folder"></i> Kategori
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menus.index') }}">
                        <i class="fas fa-compass"></i> Menu Navigasi
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="fas fa-file-alt"></i> Halaman (Redaksi & Kontak)
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tags.index') }}">
                        <i class="fas fa-tags"></i> Tag
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.videos.index') }}">
                        <i class="fab fa-youtube"></i> Video & Shorts
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.breaking-news.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.breaking-news.index') }}">
                        <i class="fas fa-bolt"></i> Breaking News
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.comments.index') }}">
                        <i class="far fa-comments"></i> Komentar
                        @php
                            $pCount = \App\Models\Comment::where('status', 'pending')->count();
                        @endphp
                        @if($pCount > 0)
                            <span class="admin-nav-badge">{{ $pCount }}</span>
                        @endif
                    </a>
                </div>

                <div class="admin-nav-category">Media & Monetisasi</div>
                <div class="admin-nav-item {{ request()->routeIs('admin.advertisements.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.advertisements.index') }}">
                        <i class="fas fa-ad"></i> Iklan & Banner
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.media.index') }}">
                        <i class="far fa-images"></i> Media Library
                    </a>
                </div>
                <div class="admin-nav-item {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.newsletters.index') }}">
                        <i class="far fa-envelope"></i> Newsletter
                    </a>
                </div>

                <div class="admin-nav-category">Sistem & Pengaturan</div>
                @if(Auth::user()->canManageAll())
                    <div class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users-cog"></i> Pengguna & Role
                        </a>
                    </div>
                    <div class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-sliders-h"></i> Pengaturan & SEO
                        </a>
                    </div>
                @endif
                <div class="admin-nav-item {{ request()->routeIs('admin.activity_logs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.activity_logs.index') }}">
                        <i class="fas fa-history"></i> Activity Log
                    </a>
                </div>

                <div class="admin-nav-category">Akses Cepat</div>
                <div class="admin-nav-item">
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                </div>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="admin-main">
            {{-- Topbar --}}
            <header class="admin-topbar">
                <div class="admin-topbar-left">
                    <button class="admin-menu-toggle" aria-label="Toggle Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 0.45rem; text-decoration: none;">
                        <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('assets/images/logo-transparent.png') }}" alt="{{ $siteSettings['site_name'] ?? 'KATA SULBAR' }}" style="height: 22px; width: auto; object-fit: contain;">
                        <span class="admin-topbar-title">Admin Panel</span>
                    </a>
                </div>

                <div class="admin-topbar-right">
                    <div class="admin-user-menu">
                        <div class="admin-user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="admin-user-info">
                            <div class="admin-user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <div class="admin-user-role">{{ Auth::user()->role_label ?? 'Administrator' }}</div>
                        </div>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm" title="Keluar">
                            <i class="fas fa-sign-out-alt"></i> <span class="btn-logout-text">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Body Content --}}
            <main class="admin-content">
                {{-- Flash Notifications --}}
                @if(session('success'))
                    <div class="admin-alert admin-alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="admin-alert admin-alert-danger">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="admin-alert admin-alert-danger">
                        <ul style="margin-left: 1rem; list-style: disc;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Admin JS --}}
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
