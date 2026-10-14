@extends('frontend.layouts.app')

@section('title', 'Pencarian: ' . ($query ?: 'Semua') . ' - KATA SULBAR')

@section('content')
<div class="container">

    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span>Pencarian</span>
        @if($query)
            <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: var(--text-primary); font-weight: 600;">"{{ $query }}"</span>
        @endif
    </nav>

    <div style="background: var(--bg-surface); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 2rem;">
        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--navy-blue); margin-bottom: 0.75rem;">
            Hasil Pencarian: "{{ $query }}"
        </h1>
        <p style="font-size: 0.875rem; color: var(--text-muted);">
            Ditemukan {{ $articles->total() }} berita yang relevan dengan kata kunci tersebut.
        </p>
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
            <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 2rem;">
                @forelse($articles as $art)
                    <article style="display: flex; gap: 1rem; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; padding: 0.85rem; transition: all 0.2s;" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='none'">
                        <div style="width: 180px; height: 110px; flex-shrink: 0; border-radius: var(--radius-sm); overflow: hidden;">
                            <img src="{{ $art->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $art->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center;">
                            <span style="align-self: flex-start; font-size: 0.6875rem; font-weight: 700; color: #fff; background: {{ $art->category ? $art->category->color : 'var(--primary-red)' }}; padding: 0.15rem 0.5rem; border-radius: 3px; text-transform: uppercase; margin-bottom: 0.35rem;">
                                {{ $art->category ? $art->category->name : 'Sulbar' }}
                            </span>
                            <h3 style="font-size: 1.05rem; font-weight: 700; line-height: 1.35; margin-bottom: 0.4rem;">
                                <a href="{{ route('news.show', $art->slug) }}">{{ $art->title }}</a>
                            </h3>
                            <p style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $art->excerpt }}
                            </p>
                            <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; gap: 1rem;">
                                <span><i class="far fa-calendar-alt"></i> {{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : '' }}</span>
                                <span><i class="far fa-eye"></i> {{ $art->formatted_views }} dibaca</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 3rem 1rem; background: var(--bg-surface); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        <i class="fas fa-search" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 0.75rem;"></i>
                        <p style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary); margin-bottom: 0.25rem;">Tidak Ditemukan Berita</p>
                        <p style="font-size: 0.875rem; color: var(--text-muted);">Coba gunakan kata kunci lain yang lebih umum atau periksa ejaan kata.</p>
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
