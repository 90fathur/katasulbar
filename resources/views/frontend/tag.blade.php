@extends('frontend.layouts.app')

@section('title', 'Tag #' . $tag->name . ' - KATA SULBAR')

@section('content')
<div class="container">

    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span>Tag</span>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">#{{ $tag->name }}</span>
    </nav>

    <div style="background: var(--navy-blue); color: #fff; padding: 1.75rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
        <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #94A3B8;">Topik Berita</span>
        <h1 style="font-size: 2rem; font-weight: 800; margin: 0.25rem 0;">#{{ $tag->name }}</h1>
        <p style="font-size: 0.875rem; color: #CBD5E1;">Menampilkan seluruh artikel dengan tagar #{{ $tag->name }}</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;" class="category-main-grid">
        <style>
            @media (min-width: 992px) {
                .category-main-grid {
                    grid-template-columns: 8.5fr 3.5fr !important;
                }
            }
        </style>

        <div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                @forelse($articles as $art)
                    <article class="news-card">
                        <div class="news-card-thumb-wrap">
                            <span class="news-card-category">
                                {{ $art->category ? $art->category->name : 'Sulbar' }}
                            </span>
                            <a href="{{ route('news.show', $art->slug) }}">
                                <img src="{{ $art->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $art->title }}" class="news-card-thumb" loading="lazy">
                            </a>
                        </div>
                        <div class="news-card-body">
                            <h3 class="news-card-title">
                                <a href="{{ route('news.show', $art->slug) }}">{{ $art->title }}</a>
                            </h3>
                            <div class="news-card-meta">
                                <span><i class="far fa-calendar-alt"></i> {{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : '' }}</span>
                                <span><i class="far fa-eye"></i> {{ $art->formatted_views }}</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: var(--bg-surface); border-radius: var(--radius-md);">
                        <p style="font-weight: 600; color: var(--text-muted);">Belum ada berita dengan tag ini.</p>
                    </div>
                @endforelse
            </div>

            <div style="margin: 2rem 0; display: flex; justify-content: center;">
                {{ $articles->links() }}
            </div>
        </div>

        <aside>
            <div class="widget-box mb-4">
                <div style="padding: 0.85rem 1rem; border-bottom: 2px solid var(--primary-red); font-weight: 800; font-size: 0.9375rem; color: var(--navy-blue);">
                    <i class="fas fa-fire" style="color:var(--primary-red)"></i> Terpopuler
                </div>
                <div style="padding: 0.75rem;">
                    <div class="popular-list">
                        @foreach($popularArticles as $r => $p)
                            <div class="popular-item">
                                <div class="popular-rank">{{ sprintf('%02d', $r + 1) }}</div>
                                <img src="{{ $p->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" class="popular-thumb" alt="{{ $p->title }}">
                                <div class="popular-info">
                                    <a href="{{ route('news.show', $p->slug) }}" class="popular-title">{{ $p->title }}</a>
                                    <div class="popular-meta">
                                        <span><i class="far fa-eye"></i> {{ $p->formatted_views }} dibaca</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @include('frontend.partials.ad-slot', ['position' => 'sidebar_right'])
        </aside>
    </div>

</div>
@endsection
