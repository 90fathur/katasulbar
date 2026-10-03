@extends('frontend.layouts.app')

@section('title', $video->title . ' - Galeri Video HALO SULBAR')
@section('meta_description', Str::limit($video->description, 160))
@section('og_type', 'video.other')
@section('og_image', $video->thumbnail ?: 'https://img.youtube.com/vi/' . $video->youtube_id . '/maxresdefault.jpg')

@section('content')
<div class="container">

    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <a href="{{ route('video.index') }}">Video</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">{{ Str::limit($video->title, 40) }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;" class="detail-main-layout">
        <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;" class="article-sidebar-grid">
            <style>
                @media (min-width: 992px) {
                    .article-sidebar-grid {
                        grid-template-columns: 8fr 4fr !important;
                    }
                }
            </style>

            {{-- Main Video Player Column --}}
            <div>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--navy-blue); line-height: 1.35; margin-bottom: 1rem;">
                    {{ $video->title }}
                </h1>

                {{-- Player container --}}
                <div style="width: 100%; border-radius: var(--radius-md); overflow: hidden; background: #000; margin-bottom: 1.25rem; box-shadow: var(--shadow-md); {{ $video->is_short ? 'max-width: 420px; margin-left: auto; margin-right: auto;' : '' }}">
                    <div style="aspect-ratio: {{ $video->is_short ? '9 / 16' : '16 / 9' }}; width: 100%;">
                        <iframe src="https://www.youtube-nocookie.com/embed/{{ $video->youtube_id }}?rel=0" style="width: 100%; height: 100%; border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>

                {{-- Video Metadata & Social Share --}}
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); margin-bottom: 1.5rem;">
                    <div style="font-size: 0.8125rem; color: var(--text-muted); display: flex; gap: 1rem;">
                        <span><i class="far fa-calendar-alt"></i> {{ $video->published_at ? $video->published_at->translatedFormat('d F Y') : '' }}</span>
                        <span><i class="far fa-eye"></i> {{ $video->formatted_views }} ditonton</span>
                        <span><i class="fas fa-folder"></i> {{ $video->category ? $video->category->name : 'Video' }}</span>
                    </div>

                    <div class="social-share-bar">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($video->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="share-btn wa" title="Bagikan ke WhatsApp">
                            <i class="fab fa-whatsapp"></i> WA
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="share-btn fb" title="Bagikan ke Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($video->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="share-btn x" title="Bagikan ke X">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <button type="button" class="share-btn copy" title="Salin Tautan">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>
                </div>

                {{-- Video Description --}}
                <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--navy-blue); margin-bottom: 0.75rem;">Deskripsi Video</h3>
                    <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6;">
                        {{ $video->description ?: 'Liputan video eksklusif persembahan tim redaksi multimedia HALO SULBAR.' }}
                    </p>
                </div>

                {{-- Related Videos --}}
                @if($relatedVideos->count() > 0)
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-film" style="color:var(--primary-red)"></i> Video Terkait
                        </h3>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                        @foreach($relatedVideos as $rv)
                            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                                <div style="position: relative; aspect-ratio: 16 / 9;">
                                    <a href="{{ route('video.show', $rv->slug) }}">
                                        <img src="{{ $rv->thumbnail ?: 'https://img.youtube.com/vi/' . $rv->youtube_id . '/hqdefault.jpg' }}" alt="{{ $rv->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                </div>
                                <div style="padding: 0.75rem;">
                                    <h4 style="font-size: 0.85rem; font-weight: 700; line-height: 1.35; margin-bottom: 0.25rem;">
                                        <a href="{{ route('video.show', $rv->slug) }}">{{ $rv->title }}</a>
                                    </h4>
                                    <div style="font-size: 0.7rem; color: var(--text-muted);"><i class="far fa-eye"></i> {{ $rv->formatted_views }} ditonton</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <aside>
                {{-- Video Terbaru --}}
                <div class="widget-box mb-4">
                    <div style="padding: 0.85rem 1rem; border-bottom: 2px solid var(--primary-red); font-weight: 800; font-size: 0.9375rem; color: var(--navy-blue);">
                        <i class="fas fa-video" style="color:var(--primary-red)"></i> Video Terbaru Lainnya
                    </div>
                    <div style="padding: 0.75rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($latestVideos as $lv)
                            <div class="video-side-card">
                                <div class="video-side-thumb-wrap" style="width: 100px; height: 65px;">
                                    <a href="{{ route('video.show', $lv->slug) }}">
                                        <img src="{{ $lv->thumbnail ?: 'https://img.youtube.com/vi/' . $lv->youtube_id . '/hqdefault.jpg' }}" alt="{{ $lv->title }}" class="video-side-thumb">
                                    </a>
                                    <span class="video-side-duration">{{ $lv->duration }}</span>
                                </div>
                                <div class="video-side-info">
                                    <h4 class="video-side-title">
                                        <a href="{{ route('video.show', $lv->slug) }}">{{ $lv->title }}</a>
                                    </h4>
                                    <div class="video-side-meta"><i class="far fa-eye"></i> {{ $lv->formatted_views }} ditonton</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @include('frontend.partials.ad-slot', ['position' => 'sidebar_right'])
            </aside>

        </div>
    </div>

</div>
@endsection
