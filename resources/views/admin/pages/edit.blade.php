@extends('admin.layouts.app')

@section('title', 'Edit Halaman: ' . $page->title . ' - KATA SULBAR Admin')

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
        <h1 class="admin-page-title">Edit Halaman: {{ $page->title }}</h1>
        <p class="admin-page-subtitle">
            Perbarui teks, tata letak, susunan pengurus, informasi kontak, dan metadata SEO secara dinamis.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ url('/' . $page->slug) }}" target="_blank" class="admin-btn admin-btn-navy">
            <i class="fas fa-external-link-alt"></i> Lihat di Web
        </a>
        <a href="{{ route('admin.pages.index') }}" class="admin-btn" style="background: #E2E8F0; color: #334155;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="page-edit-grid">
        <style>
            @media (min-width: 992px) {
                .page-edit-grid {
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
                        <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required placeholder="Contoh: Struktur Redaksi">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug URL *</label>
                        <div style="display: flex; align-items: center; gap: 0.35rem;">
                            <span style="font-size: 0.75rem; color: var(--admin-text-muted); font-family: monospace;">{{ url('/') }}/</span>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" required placeholder="redaksi" style="font-family: monospace;">
                        </div>
                        @if(in_array($page->slug, ['redaksi', 'tentang-kami', 'kontak', 'pedoman-media-siber']))
                            <div style="font-size: 0.6875rem; color: #D97706; margin-top: 0.25rem;">
                                <i class="fas fa-info-circle"></i> Ini adalah halaman bawaan sistem. Disarankan tetap menggunakan slug <code>{{ $page->slug }}</code> agar seluruh menu otomatis terhubung.
                            </div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Isi Halaman Lengkap (Editor Teks Kaya) *</label>
                        <textarea name="content" id="summernoteEditor" required>{{ old('content', $page->content) }}</textarea>
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
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Judul pada tab browser & hasil pencarian Google">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Description (Opsional)</label>
                        <textarea name="meta_description" rows="2" class="form-control" placeholder="Ringkasan 1-2 kalimat untuk snippet Google">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div>
            {{-- Publication Card --}}
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
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                <span class="admin-slider"></span>
                            </label>
                            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--admin-navy);">
                                Tampilkan di Website (Aktif)
                            </span>
                        </div>
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.75rem; margin-bottom: 1rem; font-size: 0.72rem; color: var(--admin-text-muted);">
                        <div><strong>Dibuat pada:</strong> {{ $page->created_at ? $page->created_at->format('d F Y H:i') : '-' }}</div>
                        <div style="margin-top: 0.25rem;"><strong>Terakhir diperbarui:</strong> {{ $page->updated_at ? $page->updated_at->format('d F Y H:i') : '-' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem 1rem;">
                            <i class="fas fa-save"></i> Simpan Perubahan Halaman
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="admin-btn" style="background: #F1F5F9; color: #475569; width: 100%; justify-content: center;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contextual Tips Card --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">
                        <i class="fas fa-lightbulb" style="color:#EAB308"></i> Panduan Konten
                    </div>
                </div>
                <div class="admin-card-body" style="font-size: 0.75rem; line-height: 1.5; color: var(--admin-text-muted);">
                    @if($page->slug === 'redaksi')
                        <p style="margin-bottom: 0.5rem;"><strong>Tips Struktur Redaksi:</strong></p>
                        <ul style="padding-left: 1.25rem; margin: 0; list-style-type: disc;">
                            <li>Gunakan bullet points atau tabel untuk susunan pengurus (Pemimpin Redaksi, Redaktur Pelaksana, Reporter).</li>
                            <li>Dapat mencantumkan penanggung jawab liputan per kabupaten se-Sulawesi Barat.</li>
                        </ul>
                    @elseif($page->slug === 'tentang-kami')
                        <p style="margin-bottom: 0.5rem;"><strong>Tips Tentang Kami:</strong></p>
                        <ul style="padding-left: 1.25rem; margin: 0; list-style-type: disc;">
                            <li>Cantumkan visi & misi KATA SULBAR.</li>
                            <li>Jelaskan tagline <em>“Suara Sulbar, Untuk Semua”</em> dan komitmen jurnalisme berimbang.</li>
                        </ul>
                    @elseif($page->slug === 'kontak')
                        <p style="margin-bottom: 0.5rem;"><strong>Tips Halaman Kontak:</strong></p>
                        <ul style="padding-left: 1.25rem; margin: 0; list-style-type: disc;">
                            <li>Sertakan alamat kantor fisik redaksi di Mamuju.</li>
                            <li>Nomor kontak WhatsApp dan email pengaduan redaksi atau kerja sama pemasangan iklan.</li>
                        </ul>
                    @else
                        <p style="margin: 0;">
                            Gunakan tombol format teks pada editor untuk mempertegas judul bab, paragraf, kutipan, tautan, dan daftar berurutan.
                        </p>
                    @endif
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
