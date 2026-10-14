@extends('admin.layouts.app')

@section('title', 'Tulis Berita Baru - KATA SULBAR Admin')

@push('styles')
{{-- Summernote Lite WYSIWYG Editor --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border-color: var(--admin-border);
        border-radius: 4px;
        font-family: inherit;
    }
    .note-toolbar {
        background-color: #F8FAFC !important;
        border-bottom-color: var(--admin-border) !important;
    }
</style>
@endpush

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tulis Berita Baru</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Lengkapi formulir di bawah ini untuk menerbitkan liputan berita terbaru di Kata Sulbar.
        </p>
    </div>
    <a href="{{ route('admin.articles.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="article-form-grid">
        <style>
            @media (min-width: 992px) {
                .article-form-grid {
                    grid-template-columns: 8fr 4fr !important;
                }
            }
        </style>

        {{-- Main Column (Editorial content) --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Judul Berita *</label>
                        <input type="text" name="title" id="articleTitle" class="form-control" style="font-size: 1rem; font-weight: 700; padding: 0.65rem;" required value="{{ old('title') }}" placeholder="Masukkan judul berita yang menarik dan informatif...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug URL</label>
                        <input type="text" name="slug" id="articleSlug" class="form-control" value="{{ old('slug') }}" placeholder="otomatis-dibuat-dari-judul">
                        <small style="font-size: 0.7rem; color: var(--admin-text-muted);">Biarkan kosong untuk generate otomatis sesuai kaidah SEO.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ringkasan / Excerpt</label>
                        <textarea name="excerpt" rows="3" class="form-control" placeholder="Tuliskan 1-2 kalimat ringkasan penting untuk cuplikan berita dan meta description...">{{ old('excerpt') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Isi Berita Lengkap *</label>
                        <textarea name="content" id="summernoteEditor" required>{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SEO Optimization Card (Section 13) --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-search" style="color:var(--admin-red)"></i> Optimasi SEO & Metadata</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Judul khusus untuk mesin pencari Google (opsional)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="form-control" placeholder="Deskripsi meta untuk snippet Google (maks. 160 karakter)">{{ old('meta_description') }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" placeholder="sulbar, berita mamuju, pemprov sulbar, ...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Column (Publishing, Categories, Attributes) --}}
        <div>
            {{-- Publish Box --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-paper-plane" style="color:var(--admin-red)"></i> Pengaturan Publikasi</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Status Berita *</label>
                        <select name="status" class="form-control">
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publikasikan Langsung (Published)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                            <option value="review" {{ old('status') === 'review' ? 'selected' : '' }}>Kirim untuk Review Editor</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Arsipkan (Archived)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Editor Penanggung Jawab</label>
                        <select name="editor_id" class="form-control">
                            <option value="">Pilih Editor</option>
                            @foreach($editors as $ed)
                                <option value="{{ $ed->id }}" {{ old('editor_id') == $ed->id ? 'selected' : '' }}>{{ $ed->name }} ({{ $ed->role_label }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jadwal / Waktu Terbit</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                        <small style="font-size: 0.7rem; color: var(--admin-text-muted);">Biarkan waktu sekarang untuk publikasi langsung.</small>
                    </div>

                    {{-- Highlight Flags --}}
                    <div style="background: #F8FAFC; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.85rem; margin-bottom: 1.25rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_headline" value="1" {{ old('is_headline') ? 'checked' : '' }}>
                            <strong>Headline Hero Carousel</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking') ? 'checked' : '' }}>
                            <strong>Breaking News Ticker</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_editor_pick" value="1" {{ old('is_editor_pick') ? 'checked' : '' }}>
                            <strong>Pilihan Redaksi</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <strong>Berita Pendamping Hero</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', '1') ? 'checked' : '' }}>
                            Izinkan Komentar Pembaca
                        </label>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem;">
                        <i class="fas fa-save"></i> Simpan & Terbitkan
                    </button>
                </div>
            </div>

            {{-- Category & Tags Box --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-folder" style="color:var(--admin-navy)"></i> Kategori & Tag</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Kategori Utama *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Tag Berita (Pisahkan dengan Koma)</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="Mamuju, Infrastruktur, Pemprov Sulbar">
                        <div style="font-size: 0.7rem; color: var(--admin-text-muted); margin-top: 0.35rem;">
                            Contoh tag populer: Mamuju, Majene, Polman, BMKG, Pariwisata Sulbar.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thumbnail / Foto Utama --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="far fa-image" style="color:var(--admin-red)"></i> Foto Utama Berita</div>
                </div>
                <div class="admin-card-body">
                    <div style="margin-bottom: 1rem; text-align: center;">
                        <img id="thumbPreview" src="{{ asset('assets/images/sulbar_panorama.jpg') }}" alt="Preview Foto" style="width: 100%; max-height: 180px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Upload File Gambar</label>
                        <input type="file" name="thumbnail_file" class="form-control" accept="image/*" data-preview="thumbPreview">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Atau Masukkan URL Gambar</label>
                        <input type="text" name="thumbnail" class="form-control" value="{{ old('thumbnail') }}" placeholder="https://...">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Keterangan / Caption Foto</label>
                        <input type="text" name="thumbnail_caption" class="form-control" value="{{ old('thumbnail_caption') }}" placeholder="Foto: Dok. Humas Pemprov Sulbar">
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#summernoteEditor').summernote({
        placeholder: 'Tuliskan naskah berita lengkap di sini. Gunakan heading, bold, quote, atau embed media...',
        tabsize: 2,
        height: 380,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
@endpush
@endsection
