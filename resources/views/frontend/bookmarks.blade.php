@extends('frontend.layouts.app')

@section('title', 'Berita Tersimpan (Bookmarks) - HALO SULBAR')

@section('content')
<div class="container">

    <nav class="breadcrumb-bar" aria-label="breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
        <span style="color: var(--text-primary); font-weight: 600;">Berita Tersimpan</span>
    </nav>

    <div style="background: var(--navy-blue); color: #fff; padding: 2rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.35rem;">
            <i class="fas fa-bookmark" style="color:var(--primary-red)"></i> Berita Tersimpan
        </h1>
        <p style="font-size: 0.875rem; color: #CBD5E1;">
            Daftar artikel yang telah Anda tandai untuk dibaca nanti.
        </p>
    </div>

    <div id="bookmarksList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 3rem;">
        {{-- Populated via LocalStorage JavaScript --}}
    </div>

    <div id="emptyBookmarks" style="display: none; text-align: center; padding: 3rem 1rem; background: var(--bg-surface); border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 3rem;">
        <i class="far fa-bookmark" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 0.75rem;"></i>
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem;">Belum Ada Berita yang Disimpan</h3>
        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.25rem;">Klik tombol simpan / bookmark pada artikel berita untuk menyimpannya di sini.</p>
        <a href="{{ route('home') }}" class="hero-cta-btn" style="display: inline-flex;">Eksplor Berita Sekarang</a>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('bookmarksList');
    const emptyBox = document.getElementById('emptyBookmarks');
    const bookmarks = JSON.parse(localStorage.getItem('halosulbar_bookmarks') || '[]');

    if (!bookmarks.length) {
        list.style.display = 'none';
        emptyBox.style.display = 'block';
    } else {
        emptyBox.style.display = 'none';
        let html = '';
        bookmarks.forEach(item => {
            html += `
                <article class="news-card">
                    <div class="news-card-thumb-wrap">
                        <img src="${item.thumbnail}" alt="${item.title}" class="news-card-thumb">
                    </div>
                    <div class="news-card-body">
                        <h3 class="news-card-title">
                            <a href="${item.url}">${item.title}</a>
                        </h3>
                        <div class="news-card-meta">
                            <span>${item.date}</span>
                            <button onclick="removeBookmark('${item.url}')" style="background:none;border:none;color:var(--primary-red);cursor:pointer;font-size:0.75rem;">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                </article>
            `;
        });
        list.innerHTML = html;
    }
});

function removeBookmark(url) {
    let bookmarks = JSON.parse(localStorage.getItem('halosulbar_bookmarks') || '[]');
    bookmarks = bookmarks.filter(b => b.url !== url);
    localStorage.setItem('halosulbar_bookmarks', JSON.stringify(bookmarks));
    location.reload();
}
</script>
@endpush
@endsection
