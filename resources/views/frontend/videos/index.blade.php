@extends('frontend.layouts.app')

@section('title', 'Video Berita & Liputan Khusus Sulawesi Barat - HALO SULBAR')
@section('meta_description', 'Kumpulan galeri video berita, live streaming, dan video singkat YouTube Shorts seputar Sulawesi Barat.')

@section('content')
<div class="container">

    {{-- Breadcrumbs --}}
    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">Video Halo Sulbar</span>
    </nav>

    {{-- Video Portal Header Banner --}}
    <div style="background: linear-gradient(135deg, var(--navy-blue) 0%, #074C8C 100%); color: #fff; padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; background: var(--primary-red); color: #fff; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.6rem;">
                <i class="fas fa-play"></i> Video Portal
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 900; margin-bottom: 0.4rem;">Galeri Video Halo Sulbar</h1>
            <p style="font-size: 0.95rem; color: #CBD5E1; max-width: 650px;">
                Saksikan liputan langsung, liputan investigasi, pesona wisata daerah, dan video singkat dari 6 kabupaten di Sulawesi Barat.
            </p>
        </div>
    </div>

    {{-- Category Filters --}}
    <div style="display: flex; gap: 0.5rem; overflow-x: auto; padding-bottom: 1rem; margin-bottom: 1.5rem;" class="video-filter-bar">
        <a href="{{ route('video.index') }}" style="padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 700; white-space: nowrap; {{ empty($categorySlug) && empty($videoType) ? 'background:var(--primary-red); color:#fff;' : 'background:var(--bg-surface); border:1px solid var(--border-color); color:var(--text-secondary);' }}">
            Semua Video
        </a>
        <a href="{{ route('video.index', ['tipe' => 'shorts']) }}" style="padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 700; white-space: nowrap; {{ $videoType === 'shorts' ? 'background:var(--primary-red); color:#fff;' : 'background:var(--bg-surface); border:1px solid var(--border-color); color:var(--text-secondary);' }}">
            <i class="fab fa-youtube"></i> Shorts
        </a>
        @foreach($categories as $vc)
            <a href="{{ route('video.index', ['kategori' => $vc->slug]) }}" style="padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 700; white-space: nowrap; {{ $categorySlug === $vc->slug ? 'background:var(--primary-red); color:#fff;' : 'background:var(--bg-surface); border:1px solid var(--border-color); color:var(--text-secondary);' }}">
                {{ $vc->name }}
            </a>
        @endforeach
    </div>

    {{-- Featured Video (if on all videos) --}}
    @if(empty($categorySlug) && empty($videoType) && $featuredVideo)
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2.5rem;" class="featured-video-highlight">
            <style>
                @media (min-width: 992px) {
                    .featured-video-highlight {
                        grid-template-columns: 7fr 5fr !important;
                        align-items: center;
                    }
                }
            </style>

            <div class="featured-video-player-wrap" data-video-id="{{ $featuredVideo->youtube_id }}" data-is-shorts="false" style="margin-bottom: 0;">
                <img src="{{ $featuredVideo->thumbnail ?: 'https://img.youtube.com/vi/' . $featuredVideo->youtube_id . '/maxresdefault.jpg' }}" alt="{{ $featuredVideo->title }}" class="featured-video-thumb">
                <div class="featured-video-play-center">
                    <i class="fas fa-play"></i>
                </div>
                <span class="featured-video-duration">{{ $featuredVideo->duration }}</span>
            </div>

            <div style="padding: 1rem;">
                <span style="font-size: 0.75rem; font-weight: 800; color: var(--primary-red); text-transform: uppercase;">
                    <i class="fas fa-star"></i> Featured Video
                </span>
                <h2 style="font-size: 1.65rem; font-weight: 800; line-height: 1.35; margin: 0.5rem 0;">
                    <a href="{{ route('video.show', $featuredVideo->slug) }}">{{ $featuredVideo->title }}</a>
                </h2>
                <p style="font-size: 0.9375rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 1rem;">
                    {{ $featuredVideo->description }}
                </p>
                <div style="font-size: 0.8125rem; color: var(--text-muted); display: flex; gap: 1.25rem;">
                    <span><i class="far fa-eye"></i> {{ $featuredVideo->formatted_views }} ditonton</span>
                    <span><i class="far fa-calendar-alt"></i> {{ $featuredVideo->published_at ? $featuredVideo->published_at->translatedFormat('d F Y') : '' }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- Shorts Carousel Section if available --}}
    @if(empty($videoType) && isset($shortsVideos) && $shortsVideos->count() > 0)
        <div class="shorts-section" style="margin: 2rem 0; padding: 1.5rem;">
            <div class="shorts-header">
                <h3 class="section-title" style="color: #fff;">
                    <i class="fab fa-youtube" style="color:var(--primary-red)"></i> Video Singkat (Shorts)
                </h3>
            </div>
            <div class="shorts-grid">
                @foreach($shortsVideos as $sv)
                    <div class="shorts-card" data-video-id="{{ $sv->youtube_id }}" data-is-shorts="true" title="{{ $sv->title }}">
                        <img src="{{ $sv->thumbnail ?: 'https://img.youtube.com/vi/' . $sv->youtube_id . '/hqdefault.jpg' }}" alt="{{ $sv->title }}" class="shorts-thumb" loading="lazy">
                        <div class="shorts-overlay">
                            <div class="shorts-play-btn"><i class="fas fa-play"></i></div>
                            <div class="shorts-content">
                                <h4 class="shorts-title">{{ $sv->title }}</h4>
                                <div class="shorts-meta">
                                    <span>{{ $sv->formatted_views }}</span>
                                    <span>{{ $sv->duration }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Regular Video Grid --}}
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-video" style="color:var(--primary-red)"></i> Daftar Video Berita
        </h3>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        @forelse($videos as $vid)
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column;">
                <div style="position: relative; aspect-ratio: 16 / 9; overflow: hidden; cursor: pointer;" data-video-id="{{ $vid->youtube_id }}" data-is-shorts="{{ $vid->is_short ? 'true' : 'false' }}">
                    <img src="{{ $vid->thumbnail ?: 'https://img.youtube.com/vi/' . $vid->youtube_id . '/hqdefault.jpg' }}" alt="{{ $vid->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 44px; height: 44px; border-radius: 50%; background: var(--primary-red); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.4);">
                        <i class="fas fa-play"></i>
                    </div>
                    <span style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.8); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.4rem; border-radius: 3px;">
                        {{ $vid->duration }}
                    </span>
                </div>
                <div style="padding: 1rem; display: flex; flex-direction: column; flex: 1;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; line-height: 1.4; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <a href="{{ route('video.show', $vid->slug) }}">{{ $vid->title }}</a>
                    </h4>
                    <div style="margin-top: auto; font-size: 0.75rem; color: var(--text-muted); display: flex; justify-content: space-between;">
                        <span><i class="far fa-eye"></i> {{ $vid->formatted_views }} ditonton</span>
                        <span>{{ $vid->published_at ? $vid->published_at->format('d M Y') : '' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: var(--bg-surface); border-radius: var(--radius-md);">
                <p style="font-weight: 600; color: var(--text-muted);">Tidak ada video ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="margin: 2rem 0; display: flex; justify-content: center;">
        {{ $videos->links() }}
    </div>

</div>
@endsection
