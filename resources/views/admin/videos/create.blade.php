@extends('admin.layouts.app')

@section('title', 'Tambah Video Baru - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tambah Video Baru</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Integrasikan video berita YouTube atau video vertikal YouTube Shorts ke dalam portal Halo Sulbar.
        </p>
    </div>
    <a href="{{ route('admin.videos.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="article-form-grid">
        <style>
            @media (min-width: 992px) {
                .article-form-grid {
                    grid-template-columns: 8fr 4fr !important;
                }
            }
        </style>

        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fab fa-youtube" style="color:var(--admin-red)"></i> Informasi Video</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Tautan YouTube URL *</label>
                        <input type="url" name="youtube_url" id="youtubeUrl" class="form-control" style="font-size: 0.95rem; padding: 0.65rem;" required value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=... atau https://youtube.com/shorts/...">
                        <small style="font-size: 0.7rem; color: var(--admin-text-muted);">
                            Sistem otomatis mengekstrak ID video, thumbnail resmi YouTube, dan player embed responsif.
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Video *</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title') }}" placeholder="Masukkan judul liputan video...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Format / Jenis Video *</label>
                        <select name="video_type" id="videoTypeSelect" class="form-control">
                            <option value="video" {{ old('video_type') === 'video' ? 'selected' : '' }}>Video Biasa (Landscape 16:9)</option>
                            <option value="shorts" {{ old('video_type') === 'shorts' ? 'selected' : '' }}>YouTube Shorts (Vertikal 9:16)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Durasi Video</label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration', '02:30') }}" placeholder="02:30 atau 00:45">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Deskripsi Video</label>
                        <textarea name="description" rows="5" class="form-control" placeholder="Tuliskan keterangan lengkap tentang isi liputan video ini...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-search" style="color:var(--admin-red)"></i> Optimasi SEO</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="form-control">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-paper-plane" style="color:var(--admin-red)"></i> Pengaturan</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori Video</label>
                        <select name="category_id" class="form-control">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.85rem; margin-bottom: 1.25rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <strong>Featured Video Utama</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="show_homepage" value="1" {{ old('show_homepage', '1') ? 'checked' : '' }}>
                            Tampilkan di Beranda
                        </label>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem;">
                        <i class="fas fa-save"></i> Simpan Video
                    </button>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="far fa-image" style="color:var(--admin-navy)"></i> Custom Thumbnail (Opsional)</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Upload Custom Thumbnail</label>
                        <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
                        <small style="font-size: 0.7rem; color: var(--admin-text-muted);">
                            Jika dikosongkan, sistem akan otomatis mengambil thumbnail resolusi tinggi dari YouTube.
                        </small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
document.getElementById('youtubeUrl').addEventListener('input', function(e) {
    const val = e.target.value;
    if (val.includes('shorts')) {
        document.getElementById('videoTypeSelect').value = 'shorts';
    }
});
</script>
@endpush
@endsection
