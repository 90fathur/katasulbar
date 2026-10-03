@extends('frontend.layouts.app')

@section('title', $article->meta_title ?: $article->title . ' - HALO SULBAR')
@section('meta_description', $article->meta_description ?: $article->excerpt)
@section('meta_keywords', $article->meta_keywords ?: 'halo sulbar, ' . ($article->category ? $article->category->name : 'berita'))
@section('og_type', 'article')
@section('og_image', $article->thumbnail ?: asset('assets/images/sulbar_panorama.jpg'))

@section('schema_markup')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ addslashes($article->title) }}",
  "image": [
    "{{ $article->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}"
  ],
  "datePublished": "{{ $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String() }}",
  "dateModified": "{{ $article->updated_at->toIso8601String() }}",
  "author": [{
      "@@type": "Person",
      "name": "{{ $article->author ? $article->author->name : 'Redaksi Halo Sulbar' }}"
  }],
  "publisher": {
      "@@type": "Organization",
      "name": "HALO SULBAR",
      "logo": {
        "@@type": "ImageObject",
        "url": "{{ asset('assets/images/sulbar_panorama.jpg') }}"
      }
  },
  "description": "{{ addslashes($article->excerpt) }}"
}
</script>
@endsection

@section('content')
<div class="container">

    {{-- Breadcrumbs --}}
    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        @if($article->category)
            <a href="{{ route('category.show', $article->category->slug) }}">{{ $article->category->name }}</a>
            <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        @endif
        <span style="color: var(--text-primary); font-weight: 600;">{{ Str::limit($article->title, 40) }}</span>
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

            {{-- Main Article Content Column --}}
            <article class="article-main-body">
                <header class="article-header">
                    @if($article->category)
                        <a href="{{ route('category.show', $article->category->slug) }}" class="article-category-badge" style="background-color: {{ $article->category->color }};">
                            {{ $article->category->name }}
                        </a>
                    @endif

                    <h1 class="article-main-title">{{ $article->title }}</h1>

                    <div class="article-meta-bar">
                        <div class="article-meta-left">
                            <div class="article-author-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author ? $article->author->name : 'Reporter') }}&background=062B56&color=fff" alt="{{ $article->author ? $article->author->name : 'Reporter' }}" class="article-author-avatar">
                                <div>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        {{ $article->author ? $article->author->name : 'Tim Redaksi' }}
                                    </div>
                                    @if($article->editor)
                                        <div style="font-size: 0.7rem; color: var(--text-muted);">
                                            Editor: {{ $article->editor->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <span><i class="far fa-calendar-alt"></i> {{ $article->published_at ? $article->published_at->translatedFormat('l, d F Y - H:i') : '' }} WITA</span>
                            <span><i class="far fa-eye"></i> {{ $article->formatted_views }} dibaca</span>
                            <span><i class="far fa-clock"></i> {{ $article->estimated_reading_time }} menit baca</span>
                        </div>

                        {{-- Social Share Buttons (Section 23) --}}
                        <div class="social-share-bar">
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="share-btn wa" title="Bagikan ke WhatsApp">
                                <i class="fab fa-whatsapp"></i> WA
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="share-btn fb" title="Bagikan ke Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="share-btn x" title="Bagikan ke X">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener" class="share-btn tg" title="Bagikan ke Telegram">
                                <i class="fab fa-telegram-plane"></i>
                            </a>
                            <button type="button" class="share-btn copy" title="Salin Tautan">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </header>

                {{-- Foto Utama & Caption --}}
                @if($article->thumbnail)
                    <div class="article-main-image-wrap">
                        <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="article-main-image">
                        @if($article->thumbnail_caption)
                            <div class="article-caption">
                                <i class="fas fa-camera" style="margin-right: 0.35rem"></i> {{ $article->thumbnail_caption }}
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Isi Berita with dynamic in-article ad --}}
                <div class="article-body-content">
                    {!! $formattedContent !!}
                </div>

                {{-- Tag Berita --}}
                @if($article->tags && $article->tags->count() > 0)
                    <div class="article-tags-wrap">
                        <span style="font-size: 0.8125rem; font-weight: 700; color: var(--text-muted);"><i class="fas fa-tags" style="color:var(--primary-red)"></i> Tag Terkait:</span>
                        @foreach($article->tags as $t)
                            <a href="{{ route('tag.show', $t->slug) }}" class="tag-pill">#{{ $t->name }}</a>
                        @endforeach
                    </div>
                @endif

                {{-- Author Profile Box --}}
                @if($article->author)
                    <div class="author-profile-box">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author->name) }}&background=062B56&color=fff&size=128" alt="{{ $article->author->name }}" class="author-profile-avatar">
                        <div class="author-profile-info">
                            <h4>{{ $article->author->name }}</h4>
                            <div class="author-profile-role">{{ $article->author->role_label }} Halo Sulbar</div>
                            <p class="author-profile-bio">{{ $article->author->bio ?? 'Jurnalis independen yang berdedikasi mengabarkan peristiwa aktual di Sulawesi Barat.' }}</p>
                        </div>
                    </div>
                @endif

                {{-- Artikel Sebelumnya & Berikutnya --}}
                <div class="article-prev-next-nav">
                    @if($prevArticle)
                        <div class="prev-next-box">
                            <span class="prev-next-label"><i class="fas fa-arrow-left"></i> Artikel Sebelumnya</span>
                            <div class="prev-next-title">
                                <a href="{{ route('news.show', $prevArticle->slug) }}">{{ $prevArticle->title }}</a>
                            </div>
                        </div>
                    @else
                        <div></div>
                    @endif

                    @if($nextArticle)
                        <div class="prev-next-box" style="text-align: right;">
                            <span class="prev-next-label">Artikel Berikutnya <i class="fas fa-arrow-right"></i></span>
                            <div class="prev-next-title">
                                <a href="{{ route('news.show', $nextArticle->slug) }}">{{ $nextArticle->title }}</a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Berita Terkait (Related Articles) --}}
                @if($relatedArticles->count() > 0)
                    <section class="my-4">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-newspaper" style="color:var(--primary-red)"></i> Berita Terkait
                            </h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
                            @foreach($relatedArticles as $rel)
                                <article class="news-card">
                                    <div class="news-card-thumb-wrap">
                                        <a href="{{ route('news.show', $rel->slug) }}">
                                            <img src="{{ $rel->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="{{ $rel->title }}" class="news-card-thumb" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="news-card-body">
                                        <h4 class="news-card-title" style="font-size: 0.875rem;">
                                            <a href="{{ route('news.show', $rel->slug) }}">{{ $rel->title }}</a>
                                        </h4>
                                        <div class="news-card-meta">
                                            <span><i class="far fa-calendar-alt"></i> {{ $rel->published_at ? $rel->published_at->format('d M Y') : '' }}</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Komentar Section (Section 21) --}}
                <section class="comments-wrap" id="comments">
                    <h3 class="comments-header">
                        <i class="far fa-comments" style="color:var(--primary-red)"></i> Komentar ({{ $article->comments->count() }})
                    </h3>

                    {{-- Flash notification message --}}
                    @if(session('comment_success'))
                        <div style="background: #DCFCE7; color: #166534; padding: 0.85rem 1.25rem; border-radius: 4px; margin-bottom: 1.5rem; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> {{ session('comment_success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div style="background: #FEE2E2; color: #991B1B; padding: 0.85rem 1.25rem; border-radius: 4px; margin-bottom: 1.5rem; font-weight: 600;">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @php
                        $num1 = rand(1, 9);
                        $num2 = rand(1, 9);
                        $sum = $num1 + $num2;
                    @endphp

                    <form action="{{ route('news.comment', $article->id) }}" method="POST" class="comment-form">
                        @csrf
                        <div class="comment-input-row">
                            <div>
                                <label style="font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem; display: block;">Nama Lengkap *</label>
                                <input type="text" name="name" class="comment-input" required placeholder="Nama Anda" value="{{ old('name') }}">
                            </div>
                            <div>
                                <label style="font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem; display: block;">Alamat Email * (tidak dipublikasikan)</label>
                                <input type="email" name="email" class="comment-input" required placeholder="email@contoh.com" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem; display: block;">Tulis Komentar *</label>
                            <textarea name="content" rows="4" class="comment-textarea" required placeholder="Sampaikan pendapat atau tanggapan Anda dengan bahasa yang santun...">{{ old('content') }}</textarea>
                        </div>

                        {{-- Simple Math Anti-Spam Captcha --}}
                        <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                            <span style="font-size: 0.875rem; font-weight: 700; color: var(--navy-blue);">
                                Verifikasi Keamanan: Berapa {{ $num1 }} + {{ $num2 }} =
                            </span>
                            <input type="hidden" name="captcha_expected" value="{{ $sum }}">
                            <input type="number" name="captcha_answer" class="comment-input" style="width: 80px;" required placeholder="?">
                        </div>

                        <button type="submit" class="comment-submit-btn">
                            Kirim Komentar <i class="fas fa-paper-plane" style="margin-left: 0.35rem"></i>
                        </button>
                    </form>

                    {{-- Approved Comments List --}}
                    <div class="comments-list">
                        @forelse($article->comments as $comment)
                            <div class="comment-item">
                                <img src="{{ $comment->avatar_url }}" alt="{{ $comment->name }}" class="comment-avatar">
                                <div class="comment-body">
                                    <div class="comment-author-name">{{ $comment->name }}</div>
                                    <div class="comment-date"><i class="far fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</div>
                                    <div class="comment-content">{{ $comment->content }}</div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.9rem;">
                                Belum ada komentar pada artikel ini. Jadilah yang pertama berkomentar!
                            </div>
                        @endforelse
                    </div>
                </section>
            </article>

            {{-- Sidebar Column --}}
            <aside class="article-sidebar">
                {{-- Widget Tabbed Terpopuler --}}
                <div class="widget-box mb-4">
                    <div class="tab-header">
                        <button class="tab-btn active" data-tab="side-terpopuler">Terpopuler</button>
                        <button class="tab-btn" data-tab="side-terbaru">Terbaru</button>
                    </div>

                    <div class="tab-pane active" id="side-terpopuler">
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

                    <div class="tab-pane" id="side-terbaru">
                        <div class="popular-list">
                            @foreach($latestArticles as $r => $l)
                                <div class="popular-item">
                                    <div class="popular-rank">{{ sprintf('%02d', $r + 1) }}</div>
                                    <img src="{{ $l->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" class="popular-thumb" alt="{{ $l->title }}">
                                    <div class="popular-info">
                                        <a href="{{ route('news.show', $l->slug) }}" class="popular-title">{{ $l->title }}</a>
                                        <div class="popular-meta">
                                            <span><i class="far fa-clock"></i> {{ $l->published_at ? $l->published_at->diffForHumans() : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Widget Video Terpopuler (Section 35) --}}
                @if(isset($sidebarVideos) && $sidebarVideos->count() > 0)
                    <div class="widget-box mb-4">
                        <div style="padding: 0.85rem 1rem; border-bottom: 2px solid var(--primary-red); font-weight: 800; font-size: 0.9375rem; color: var(--navy-blue);">
                            <i class="fas fa-play-circle" style="color:var(--primary-red)"></i> Video Terpopuler
                        </div>
                        <div style="padding: 0.75rem; display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($sidebarVideos as $sv)
                                <div class="video-side-card" data-video-id="{{ $sv->youtube_id }}" data-is-shorts="false">
                                    <div class="video-side-thumb-wrap" style="width: 100px; height: 65px;">
                                        <img src="{{ $sv->thumbnail ?: 'https://img.youtube.com/vi/' . $sv->youtube_id . '/hqdefault.jpg' }}" alt="{{ $sv->title }}" class="video-side-thumb">
                                        <span class="video-side-duration">{{ $sv->duration }}</span>
                                    </div>
                                    <div class="video-side-info">
                                        <h4 class="video-side-title">{{ $sv->title }}</h4>
                                        <div class="video-side-meta"><i class="far fa-eye"></i> {{ $sv->formatted_views }} ditonton</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Space Iklan Sidebar 300x600 --}}
                @include('frontend.partials.ad-slot', ['position' => 'sidebar_right'])
            </aside>

        </div>
    </div>

</div>
@endsection
