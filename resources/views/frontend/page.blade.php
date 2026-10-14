@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title . ' - KATA SULBAR')
@section('meta_description', $page->meta_description ?: Str::limit(strip_tags($page->content), 150))

@section('content')
<div class="container">

    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">{{ $page->title }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;" class="category-main-grid">
        <style>
            @media (min-width: 992px) {
                .category-main-grid {
                    grid-template-columns: 8.5fr 3.5fr !important;
                }
            }
        </style>

        {{-- Main Page Content --}}
        <div>
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 2rem; box-shadow: var(--shadow-sm);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--primary-red); flex-wrap: wrap; gap: 0.75rem;">
                    <h1 style="font-size: 2rem; font-weight: 800; color: var(--navy-blue); margin: 0;">
                        {{ $page->title }}
                    </h1>
                    @if(Auth::check() && Auth::user()->canManageAll())
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="ad-banner-btn" style="padding: 0.4rem 0.85rem; font-size: 0.75rem; background: var(--navy-blue);">
                            <i class="fas fa-edit"></i> Edit Halaman Ini (Admin)
                        </a>
                    @endif
                </div>

                <div class="article-body-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside>
            <div class="widget-box mb-4">
                <div style="padding: 0.85rem 1rem; border-bottom: 2px solid var(--primary-red); font-weight: 800; font-size: 0.9375rem; color: var(--navy-blue);">
                    <i class="fas fa-fire" style="color:var(--primary-red)"></i> Berita Terpopuler
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
