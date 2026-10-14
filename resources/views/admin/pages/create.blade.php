@extends('admin.layouts.app')

@section('title', 'Tambah Halaman Baru - KATA SULBAR Admin')

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
        <h1 class="admin-page-title">Tambah Halaman Baru</h1>
        <p class="admin-page-subtitle">
            Buat halaman statis atau informasi publik baru untuk portal berita KATA SULBAR.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.pages.index') }}" class="admin-btn" style="background: #E2E8F0; color: #334155;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<form action="{{ route('admin.pages.store') }}" method="POST">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="page-create-grid">
        <style>
            @media (min-width: 992px) {
                .page-create-grid {
                    grid-template-columns: 8fr 4fr !important;
                }
            }
        </style>

        {{-- Main Content Column --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">
                        <i class="fas fa-file-signature" style="color:var(--admin-red)"></i> Konten Utama Halaman
                    </div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Judul Halaman *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Contoh: Kode Etik Internal">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug URL (Opsional, otomatis dibuat jika kosong)</label>
                        <div style="display: flex; align-items: center; gap: 0.35rem;">
                            <span style="font-size: 0.75rem; color: var(--admin-text-muted); font-family: monospace;">{{ url('/') }}/</span>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="kode-etik-internal" style="font-family: monospace;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Isi Halaman Lengkap (Editor Teks Kaya) *</label>
                        <textarea name="content" id="summernoteEditor" required>{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SEO Metadata Card --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">
                        <i class="fas fa-search" style="color:var(--admin-red)"></i> Optimasi SEO & Metadata Google
                    </div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Meta Title (Opsional)</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Judul pada tab browser & snippet Google">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Description (Opsional)</label>
                        <textarea name="meta_description" rows="2" class="form-control" placeholder="Ringkasan isi halaman">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">
                        <i class="fas fa-paper-plane" style="color:var(--admin-red)"></i> Pengaturan Publikasi
                    </div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Status Halaman</label>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.35rem;">
                            <label class="admin-switch">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="admin-slider"></span>
                            </label>
                            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--admin-navy);">
                                Tampilkan di Website (Aktif)
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem 1rem;">
                            <i class="fas fa-save"></i> Buat & Simpan Halaman
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="admin-btn" style="background: #F1F5F9; color: #475569; width: 100%; justify-content: center;">
                            Batal
                        </a>
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
        tabsize: 2,
        height: 420,
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
