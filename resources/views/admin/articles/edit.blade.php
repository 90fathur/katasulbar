@extends('admin.layouts.app')

@section('title', 'Edit Berita: ' . $article->title . ' - KATA SULBAR Admin')

@push('styles')
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
        <h1 class="admin-page-title">Edit Berita</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Perbarui konten, foto, dan pengaturan publikasi berita.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('news.show', $article->slug) }}" target="_blank" class="admin-btn admin-btn-navy">
            <i class="fas fa-external-link-alt"></i> Pratinjau di Web
        </a>
        <a href="{{ route('admin.articles.index') }}" class="admin-btn admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="article-form-grid">
        <style>
            @media (min-width: 992px) {
                .article-form-grid {
                    grid-template-columns: 8fr 4fr !important;
                }
            }
        </style>

        {{-- Main Column --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Judul Berita *</label>
                        <input type="text" name="title" id="articleTitle" class="form-control" style="font-size: 1rem; font-weight: 700; padding: 0.65rem;" required value="{{ old('title', $article->title) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug URL</label>
                        <input type="text" name="slug" id="articleSlug" class="form-control" value="{{ old('slug', $article->slug) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ringkasan / Excerpt</label>
                        <textarea name="excerpt" rows="3" class="form-control">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Isi Berita Lengkap *</label>
                        <textarea name="content" id="summernoteEditor" required>{{ old('content', $article->content) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SEO Optimization Card --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-search" style="color:var(--admin-red)"></i> Optimasi SEO & Metadata</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $article->meta_title) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="form-control">{{ old('meta_description', $article->meta_description) }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $article->meta_keywords) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-paper-plane" style="color:var(--admin-red)"></i> Pengaturan Publikasi</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Status Berita *</label>
                        <select name="status" class="form-control">
                            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="review" {{ old('status', $article->status) === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="archived" {{ old('status', $article->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Editor Penanggung Jawab</label>
                        <select name="editor_id" class="form-control">
                            <option value="">Pilih Editor</option>
                            @foreach($editors as $ed)
                                <option value="{{ $ed->id }}" {{ old('editor_id', $article->editor_id) == $ed->id ? 'selected' : '' }}>{{ $ed->name }} ({{ $ed->role_label }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jadwal / Waktu Terbit</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.85rem; margin-bottom: 1.25rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_headline" value="1" {{ old('is_headline', $article->is_headline) ? 'checked' : '' }}>
                            <strong>Headline Hero Carousel</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking', $article->is_breaking) ? 'checked' : '' }}>
                            <strong>Breaking News Ticker</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_editor_pick" value="1" {{ old('is_editor_pick', $article->is_editor_pick) ? 'checked' : '' }}>
                            <strong>Pilihan Redaksi</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                            <strong>Berita Pendamping Hero</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', $article->allow_comments) ? 'checked' : '' }}>
                            Izinkan Komentar Pembaca
                        </label>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem;">
                        <i class="fas fa-save"></i> Perbarui Berita
                    </button>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-folder" style="color:var(--admin-navy)"></i> Kategori & Tag</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Kategori Utama *</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Tag Berita (Pisahkan Koma)</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags', $selectedTags) }}">
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="far fa-image" style="color:var(--admin-red)"></i> Foto Utama Berita</div>
                </div>
                <div class="admin-card-body">
                    <div style="margin-bottom: 1rem; text-align: center;">
                        <img id="thumbPreview" src="{{ $article->thumbnail ?: asset('assets/images/sulbar_panorama.jpg') }}" alt="Preview Foto" style="width: 100%; max-height: 180px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ganti File Gambar</label>
                        <input type="file" name="thumbnail_file" class="form-control" accept="image/*" data-preview="thumbPreview">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Atau URL Gambar</label>
                        <input type="text" name="thumbnail" class="form-control" value="{{ old('thumbnail', $article->thumbnail) }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Keterangan / Caption Foto</label>
                        <input type="text" name="thumbnail_caption" class="form-control" value="{{ old('thumbnail_caption', $article->thumbnail_caption) }}">
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
    function uploadSummernoteImage(file, editor) {
        let data = new FormData();
        data.append("file", file);
        data.append("_token", "{{ csrf_token() }}");
        $.ajax({
            data: data,
            type: "POST",
            url: "{{ route('admin.media.upload') }}",
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.url) {
                    $(editor).summernote('insertImage', response.url, function($image) {
                        $image.addClass('img-fluid');
                    });
                }
            },
            error: function() {
                alert('Gagal mengunggah gambar ke server. Pastikan ukuran file di bawah 10MB.');
            }
        });
    }

    $('#summernoteEditor').summernote({
        placeholder: 'Tuliskan naskah berita lengkap di sini...',
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
        ],
        callbacks: {
            onImageUpload: function(files) {
                for (let i = 0; i < files.length; i++) {
                    uploadSummernoteImage(files[i], this);
                }
            }
        }
    });
});
</script>
@endpush
@endsection
