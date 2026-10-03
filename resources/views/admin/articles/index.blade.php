@extends('admin.layouts.app')

@section('title', 'Manajemen Berita - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Berita & Artikel</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Kelola seluruh publikasi berita, headline carousel, breaking news ticker, dan artikel pilihan redaksi.
        </p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> Tambah Berita Baru
    </a>
</div>

{{-- Filter Card --}}
<div class="admin-card" style="margin-bottom: 1.25rem;">
    <div class="admin-card-body" style="padding: 1rem;">
        <form action="{{ route('admin.articles.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" class="form-control" placeholder="Cari judul berita..." value="{{ $search }}">
            </div>
            <div style="width: 160px;">
                <select name="category_id" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 140px;">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="review" {{ $status === 'review' ? 'selected' : '' }}>Review</option>
                    <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <button type="submit" class="admin-btn admin-btn-navy">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if($search || $categoryId || $status)
                <a href="{{ route('admin.articles.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Articles Table --}}
<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Foto</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th style="text-align: center;">Headline</th>
                    <th style="text-align: center;">Breaking</th>
                    <th style="text-align: center;">Pilihan Redaksi</th>
                    <th>Status</th>
                    <th>Pembaca</th>
                    <th style="width: 120px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                    <tr>
                        <td>
                            <img src="{{ $art->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="" style="width: 60px; height: 42px; border-radius: 4px; object-fit: cover;">
                        </td>
                        <td>
                            <a href="{{ route('news.show', $art->slug) }}" target="_blank" style="font-weight: 700; color: var(--admin-navy);">
                                {{ $art->title }}
                            </a>
                            <div style="font-size: 0.7rem; color: var(--admin-text-muted); margin-top: 2px;">
                                Terbit: {{ $art->published_at ? $art->published_at->format('d/m/Y H:i') : 'Draft' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $art->category ? $art->category->color : '#062B56' }}; color: #fff;">
                                {{ $art->category ? $art->category->name : 'Sulbar' }}
                            </span>
                        </td>
                        <td style="color: var(--admin-text-main); font-weight: 600;">
                            {{ $art->author ? $art->author->name : '-' }}
                        </td>

                        {{-- Quick AJAX Switches (Section 16) --}}
                        <td style="text-align: center;">
                            <label class="switch quick-toggle-switch" title="Toggle Headline">
                                <input type="checkbox" data-article-id="{{ $art->id }}" data-field="is_headline" {{ $art->is_headline ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center;">
                            <label class="switch quick-toggle-switch" title="Toggle Breaking News">
                                <input type="checkbox" data-article-id="{{ $art->id }}" data-field="is_breaking" {{ $art->is_breaking ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center;">
                            <label class="switch quick-toggle-switch" title="Toggle Pilihan Redaksi">
                                <input type="checkbox" data-article-id="{{ $art->id }}" data-field="is_editor_pick" {{ $art->is_editor_pick ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>

                        <td>
                            @if($art->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @elseif($art->status === 'draft')
                                <span class="badge badge-warning">Draft</span>
                            @elseif($art->status === 'review')
                                <span class="badge badge-info">Review</span>
                            @else
                                <span class="badge badge-danger">Archived</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($art->views_count) }}</strong></td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('admin.articles.edit', $art->id) }}" class="admin-btn admin-btn-secondary admin-btn-sm" title="Edit Berita">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" title="Hapus Berita">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 2rem; color: var(--admin-text-muted);">
                            Tidak ada berita ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem; display: flex; justify-content: center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
