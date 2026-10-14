@extends('frontend.layouts.app')

@section('title', 'KATA SULBAR - Suara Sulbar, Untuk Semua | Portal Berita Terkini Sulawesi Barat')

@section('schema_markup')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsMediaOrganization",
  "name": "KATA SULBAR",
  "alternateName": "KataSulbar",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('assets/images/logo.png') }}",
  "slogan": "Suara Sulbar, Untuk Semua",
  "description": "Portal berita terkini dan terpercaya Sulawesi Barat.",
  "sameAs": [
    "{{ $siteSettings['facebook_url'] ?? '#' }}",
    "{{ $siteSettings['instagram_url'] ?? '#' }}",
    "{{ $siteSettings['youtube_url'] ?? '#' }}",
    "{{ $siteSettings['tiktok_url'] ?? '#' }}"
  ]
}
</script>
@endsection

@section('content')
<div class="container">

    {{-- ====================================================================
       1. HERO NEWS SECTION (MAIN HERO + 3 COMPANIONS + TERPOPULER + AD 300x600)
       ==================================================================== --}}
    <section class="hero-section">
        <div class="hero-main-grid">

            {{-- Col 1: Big Hero Slider --}}
            <div class="hero-slider-wrap">
                @foreach($heroArticles as $index => $hero)
                    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $hero->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $hero->title }}" class="hero-slide-img">
                        <div class="hero-slide-overlay">
                            <span class="hero-cat-badge" style="background-color: {{ $hero->category ? $hero->category->color : 'var(--primary-red)' }}">
                                {{ $hero->category ? $hero->category->name : 'Sulbar' }}
                            </span>
                            <h2 class="hero-title">
                                <a href="{{ route('news.show', $hero->slug) }}">{{ $hero->title }}</a>
                            </h2>
                            <div class="hero-meta">
                                <span><i class="far fa-calendar-alt"></i> {{ $hero->published_at ? $hero->published_at->translatedFormat('d F Y') : '' }}</span>
                                <span><i class="far fa-eye"></i> {{ $hero->formatted_views }} dibaca</span>
                                <span><i class="far fa-comment-dots"></i> {{ $hero->comments->count() }} komentar</span>
                            </div>
                            <p class="hero-excerpt">{{ $hero->excerpt }}</p>
                            <a href="{{ route('news.show', $hero->slug) }}" class="hero-cta-btn">
                                Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Slider Controls --}}
                <button class="hero-slider-nav prev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
                <button class="hero-slider-nav next" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>

                <div class="hero-slider-dots">
                    @foreach($heroArticles as $idx => $dot)
                        <span class="hero-dot {{ $idx === 0 ? 'active' : '' }}"></span>
                    @endforeach
                </div>
            </div>

            {{-- Col 2: Berita Pendamping (3 Stacked Compact News Cards) --}}
            <div class="hero-companion-list">
                @foreach($companionArticles as $comp)
                    <div class="companion-card">
                        <img src="{{ $comp->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $comp->title }}">
                        <div class="companion-card-overlay">
                            <span class="companion-badge" style="background-color: {{ $comp->category ? $comp->category->color : 'var(--deep-blue)' }}">
                                {{ $comp->category ? $comp->category->name : 'Berita' }}
                            </span>
                            <h3 class="companion-title">
                                <a href="{{ route('news.show', $comp->slug) }}">{{ $comp->title }}</a>
                            </h3>
                            <div class="companion-meta">
                                <span><i class="far fa-clock"></i> {{ $comp->published_at ? $comp->published_at->diffForHumans() : '' }}</span>
                                <span><i class="far fa-eye"></i> {{ $comp->formatted_views }} dibaca</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Col 3: Sidebar Terpopuler Widget --}}
            <div class="hero-widget-wrap">
                <div class="widget-box">
                    <div class="tab-header">
                        <button class="tab-btn active" data-tab="tab-terpopuler">Terpopuler</button>
                        <button class="tab-btn" data-tab="tab-terbaru">Terbaru</button>
                        <button class="tab-btn" data-tab="tab-komentar">Komentar</button>
                    </div>

                    {{-- Tab 1: Terpopuler --}}
                    <div class="tab-pane active" id="tab-terpopuler">
                        <div class="popular-list">
                            @foreach($popularArticles as $rank => $item)
                                <div class="popular-item">
                                    <div class="popular-rank">{{ sprintf('%02d', $rank + 1) }}</div>
                                    <img src="{{ $item->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" class="popular-thumb" alt="{{ $item->title }}">
                                    <div class="popular-info">
                                        <a href="{{ route('news.show', $item->slug) }}" class="popular-title">{{ $item->title }}</a>
                                        <div class="popular-meta">
                                            <span><i class="far fa-eye"></i> {{ $item->formatted_views }}</span>
                                            <span>•</span>
                                            <span>{{ $item->published_at ? $item->published_at->format('d M') : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('category.show', 'sulbar') }}" class="widget-footer-link">
                            Lihat Semua Berita <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    {{-- Tab 2: Terbaru --}}
                    <div class="tab-pane" id="tab-terbaru">
                        <div class="popular-list">
                            @foreach($latestArticles as $rank => $item)
                                <div class="popular-item">
                                    <div class="popular-rank">{{ sprintf('%02d', $rank + 1) }}</div>
                                    <img src="{{ $item->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" class="popular-thumb" alt="{{ $item->title }}">
                                    <div class="popular-info">
                                        <a href="{{ route('news.show', $item->slug) }}" class="popular-title">{{ $item->title }}</a>
                                        <div class="popular-meta">
                                            <span><i class="far fa-clock"></i> {{ $item->published_at ? $item->published_at->diffForHumans() : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab 3: Komentar --}}
                    <div class="tab-pane" id="tab-komentar">
                        <div class="popular-list">
                            @foreach($commentedArticles as $rank => $item)
                                <div class="popular-item">
                                    <div class="popular-rank">{{ sprintf('%02d', $rank + 1) }}</div>
                                    <img src="{{ $item->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" class="popular-thumb" alt="{{ $item->title }}">
                                    <div class="popular-info">
                                        <a href="{{ route('news.show', $item->slug) }}" class="popular-title">{{ $item->title }}</a>
                                        <div class="popular-meta">
                                            <span><i class="far fa-comments"></i> {{ $item->comments_count }} komentar</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Col 4: Space Iklan Sidebar 300x600 --}}
            <div class="hero-ad-wrap">
                @include('frontend.partials.ad-slot', ['position' => 'sidebar_right'])
            </div>

        </div>
    </section>

    {{-- ====================================================================
       2. HORIZONTAL BANNER IKLAN 728x90
       ==================================================================== --}}
    @include('frontend.partials.ad-slot', ['position' => 'horizontal_top', 'slotClass' => 'my-3'])

    {{-- ====================================================================
       3. BERITA TERKINI & PROMO EKSPLOR SULBAR
       ==================================================================== --}}
    <section class="my-4">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-newspaper" style="color:var(--primary-red)"></i> Berita Terkini
            </h2>
            <a href="{{ route('category.show', 'sulbar') }}" class="section-more-link">
                Lihat Semua <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;" class="berita-terkini-container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">
                @foreach($recentArticles->take(3) as $art)
                    <article class="news-card">
                        <div class="news-card-thumb-wrap">
                            <span class="news-card-category" style="background-color: {{ $art->category ? $art->category->color : 'var(--primary-red)' }}">
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
                                <span><i class="far fa-calendar-alt"></i> {{ $art->published_at ? $art->published_at->translatedFormat('d F Y') : '' }}</span>
                                <span><i class="far fa-eye"></i> {{ $art->formatted_views }} dibaca</span>
                            </div>
                        </div>
                    </article>
                @endforeach

                {{-- Eksplor Sulawesi Barat Promo Banner Card --}}
                <div class="promo-card-sulbar">
                    <div class="promo-card-content">
                        <div class="promo-card-script">Eksplor</div>
                        <h3 class="promo-card-title">Sulawesi Barat</h3>
                        <div class="promo-card-desc">Wisata • Budaya • Kuliner • Potensi Daerah</div>
                        <a href="{{ route('category.show', 'pariwisata') }}" class="promo-card-btn">
                            Jelajahi Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================================================================
       4. VIDEO SINGKAT / YOUTUBE SHORTS (9:16 VERTICAL CARDS - Section 32)
       ==================================================================== --}}
    @if(isset($shortsVideos) && $shortsVideos->count() > 0)
        <section class="shorts-section">
            <div class="container">
                <div class="shorts-header">
                    <div>
                        <h2 class="section-title">
                            <i class="fab fa-youtube" style="color:var(--primary-red); font-size:1.5rem"></i> Video Singkat
                        </h2>
                        <p style="font-size:0.8125rem; color:#CBD5E1; margin-top:0.25rem;">
                            Momen menarik, pesona wisata, dan peristiwa viral seputar Sulawesi Barat dalam video vertikal
                        </p>
                    </div>
                    <a href="{{ route('video.index', ['tipe' => 'shorts']) }}" class="section-more-link">
                        Lihat Semua Video <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="shorts-grid">
                    @foreach($shortsVideos as $short)
                        <div class="shorts-card" data-video-id="{{ $short->youtube_id }}" data-is-shorts="true" title="Tonton {{ $short->title }}">
                            <img src="{{ $short->thumbnail ?: 'https://img.youtube.com/vi/' . $short->youtube_id . '/hqdefault.jpg' }}" alt="{{ $short->title }}" class="shorts-thumb" loading="lazy">
                            <div class="shorts-overlay">
                                <div class="shorts-top-badge">
                                    <i class="fas fa-play"></i> Shorts
                                </div>
                                <div class="shorts-play-btn">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="shorts-content">
                                    <h4 class="shorts-title">{{ $short->title }}</h4>
                                    <div class="shorts-meta">
                                        <span><i class="far fa-eye"></i> {{ $short->formatted_views }}</span>
                                        <span><i class="far fa-clock"></i> {{ $short->duration }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ====================================================================
       5. KATEGORI BERITA VISUAL CARDS (Section 9)
       ==================================================================== --}}
    <section class="my-4">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-th-large" style="color:var(--primary-red)"></i> Kategori Berita
            </h2>
            <a href="{{ route('category.show', 'sulbar') }}" class="section-more-link">
                Lihat Semua <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div class="category-visual-grid">
            @foreach($visualCategories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="cat-visual-card">
                    <img src="{{ $cat->image ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $cat->name }}" class="cat-visual-bg" loading="lazy">
                    <div class="cat-visual-overlay" style="background: linear-gradient(180deg, rgba(6, 43, 86, 0.4) 0%, {{ $cat->color }}E6 100%);">
                        <span class="cat-visual-name">{{ $cat->name }}</span>
                        <div class="cat-visual-footer">
                            <span>{{ $cat->published_articles_count }} Berita</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ====================================================================
       6. VIDEO TERKINI (70% FEATURED + 30% LIST) & NEWSLETTER (Section 10 & 34)
       ==================================================================== --}}
    <section class="my-4">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-play-circle" style="color:var(--primary-red)"></i> Video Terkini
            </h2>
            <a href="{{ route('video.index') }}" class="section-more-link">
                Lihat Semua <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div class="video-section-grid">
            {{-- Col 1: 70% Featured Video Player / Card --}}
            <div>
                @if($featuredVideo)
                    <div class="featured-video-player-wrap" data-video-id="{{ $featuredVideo->youtube_id }}" data-is-shorts="false">
                        <img src="{{ $featuredVideo->thumbnail ?: 'https://img.youtube.com/vi/' . $featuredVideo->youtube_id . '/maxresdefault.jpg' }}" alt="{{ $featuredVideo->title }}" class="featured-video-thumb">
                        <div class="featured-video-play-center">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="featured-video-duration">{{ $featuredVideo->duration }}</span>
                    </div>

                    <h3 class="featured-video-title">
                        <a href="{{ route('video.show', $featuredVideo->slug) }}">{{ $featuredVideo->title }}</a>
                    </h3>

                    <div class="featured-video-meta">
                        <span><i class="far fa-calendar-alt"></i> {{ $featuredVideo->published_at ? $featuredVideo->published_at->translatedFormat('d F Y') : '' }}</span>
                        <span><i class="far fa-eye"></i> {{ $featuredVideo->formatted_views }} ditonton</span>
                        <span><i class="fas fa-folder"></i> {{ $featuredVideo->category ? $featuredVideo->category->name : 'Video' }}</span>
                    </div>
                @endif
            </div>

            {{-- Col 2: 30% List of Latest Videos + Newsletter Subscription Box --}}
            <div>
                <div class="video-side-list">
                    @foreach($recentVideos as $vid)
                        <div class="video-side-card" data-video-id="{{ $vid->youtube_id }}" data-is-shorts="false">
                            <div class="video-side-thumb-wrap">
                                <img src="{{ $vid->thumbnail ?: 'https://img.youtube.com/vi/' . $vid->youtube_id . '/hqdefault.jpg' }}" alt="{{ $vid->title }}" class="video-side-thumb" loading="lazy">
                                <span class="video-side-duration">{{ $vid->duration }}</span>
                            </div>
                            <div class="video-side-info">
                                <h4 class="video-side-title">{{ $vid->title }}</h4>
                                <div class="video-side-meta">
                                    <i class="far fa-eye"></i> {{ $vid->formatted_views }} ditonton
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Newsletter Box (Section 22) --}}
                <div class="newsletter-box">
                    <div class="newsletter-icon">
                        <i class="far fa-envelope"></i>
                    </div>
                    <h4 class="newsletter-title">Dapatkan Berita Terbaru</h4>
                    <p class="newsletter-desc">
                        Berlangganan newsletter Kata Sulbar untuk mendapatkan berita terkini langsung di email Anda setiap hari.
                    </p>
                    <form class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Masukkan email Anda..." required>
                        <button type="submit" class="newsletter-btn">Berlangganan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================================================================
       7. PILIHAN REDAKSI (EDITOR'S PICK - Section 11)
       ==================================================================== --}}
    <section class="my-4">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-bookmark" style="color:var(--primary-red)"></i> Berita Pilihan Redaksi
            </h2>
            <a href="{{ route('category.show', 'sulbar') }}" class="section-more-link">
                Lihat Semua <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div class="editor-pick-grid">
            @foreach($editorPickArticles as $ep)
                <article class="news-card">
                    <div class="news-card-thumb-wrap">
                        <span class="news-card-category" style="background-color: {{ $ep->category ? $ep->category->color : 'var(--primary-red)' }}">
                            {{ $ep->category ? $ep->category->name : 'Sulbar' }}
                        </span>
                        <a href="{{ route('news.show', $ep->slug) }}">
                            <img src="{{ $ep->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $ep->title }}" class="news-card-thumb" loading="lazy">
                        </a>
                    </div>
                    <div class="news-card-body">
                        <h3 class="news-card-title">
                            <a href="{{ route('news.show', $ep->slug) }}">{{ $ep->title }}</a>
                        </h3>
                        <div class="news-card-meta">
                            <span><i class="far fa-calendar-alt"></i> {{ $ep->published_at ? $ep->published_at->translatedFormat('d F Y') : '' }}</span>
                            <span><i class="far fa-eye"></i> {{ $ep->formatted_views }} dibaca</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ====================================================================
       8. BANNER IKLAN BAWAH HOMEPAGE 970x100 (Section 7)
       ==================================================================== --}}
    @include('frontend.partials.ad-slot', ['position' => 'home_bottom'])

</div>
@endsection
