@extends('frontend.layouts.app')

@section('title', 'Berita ' . $category->name . ' - HALO SULBAR')
@section('meta_description', $category->description ?? 'Kumpulan berita terkini seputar ' . $category->name . ' di Sulawesi Barat.')

@section('content')
<div class="container">

    {{-- Breadcrumbs --}}
    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">Kategori {{ $category->name }}</span>
    </nav>

    {{-- Category Header Banner --}}
    <div style="background: linear-gradient(135deg, var(--navy-blue) 0%, {{ $category->color }} 100%); color: #fff; padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; color: #E2E8F0;">
                <i class="fas {{ $category->icon ?: 'fa-folder' }}"></i> Kategori Berita
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 900; margin: 0.35rem 0;">{{ $category->name }}</h1>
            <p style="font-size: 0.95rem; color: #CBD5E1; max-width: 600px;">
                {{ $category->description ?? 'Menyajikan seluruh liputan informasi aktual dan mendalam terkait topik ' . $category->name . ' di wilayah Sulawesi Barat.' }}
            </p>
        </div>
    </div>

    {{-- Category Layout --}}
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;" class="category-main-grid">
        <style>
            @media (min-width: 992px) {
                .category-main-grid {
                    grid-template-columns: 8.5fr 3.5fr !important;
                }
            }
        </style>

        {{-- Left: Articles Grid --}}
        <div>
            {{-- Sorting filter bar --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); flex-wrap: wrap; gap: 0.5rem;">
                <span style="font-size: 0.875rem; font-weight: 700; color: var(--text-primary);">
                    Menampilkan {{ $articles->total() }} Berita
                </span>

                <div style="display: flex; gap: 0.5rem; font-size: 0.8125rem;">
                    <a href="{{ route('category.show', [$category->slug, 'sort' => 'terbaru']) }}" style="padding: 0.3rem 0.75rem; border-radius: 4px; font-weight: 600; {{ $sort === 'terbaru' ? 'background:var(--primary-red); color:#fff;' : 'background:var(--bg-surface); border:1px solid var(--border-color);' }}">Terbaru</a>
                    <a href="{{ route('category.show', [$category->slug, 'sort' => 'terpopuler']) }}" style="padding: 0.3rem 0.75rem; border-radius: 4px; font-weight: 600; {{ $sort === 'terpopuler' ? 'background:var(--primary-red); color:#fff;' : 'background:var(--bg-surface); border:1px solid var(--border-color);' }}">Terpopuler</a>
                    <a href="{{ route('category.show', [$category->slug, 'sort' => 'terlama']) }}" style="padding: 0.3rem 0.75rem; border-radius: 4px; font-weight: 600; {{ $sort === 'terlama' ? 'background:var(--primary-red); color:#fff;' : 'background:var(--bg-surface); border:1px solid var(--border-color);' }}">Terlama</a>
                </div>
            </div>

            {{-- Articles List --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                @forelse($articles as $art)
                    <article class="news-card">
                        <div class="news-card-thumb-wrap">
                            <span class="news-card-category" style="background-color: {{ $category->color }};">
                                {{ $category->name }}
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
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: var(--bg-surface); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        <i class="far fa-folder-open" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 0.75rem;"></i>
                        <p style="font-weight: 600; color: var(--text-muted);">Belum ada berita yang dipublikasikan dalam kategori ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            <div style="margin: 2rem 0; display: flex; justify-content: center;">
                {{ $articles->links() }}
            </div>
        </div>

        {{-- Right: Sidebar Widgets --}}
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
