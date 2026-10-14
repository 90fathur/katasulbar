@extends('admin.layouts.app')

@section('title', 'Edit Video: ' . $video->title . ' - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Edit Video</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Perbarui data video YouTube dan pengaturan tampilannya.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('video.show', $video->slug) }}" target="_blank" class="admin-btn admin-btn-navy">
            <i class="fas fa-external-link-alt"></i> Pratinjau
        </a>
        <a href="{{ route('admin.videos.index') }}" class="admin-btn admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
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

        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fab fa-youtube" style="color:var(--admin-red)"></i> Informasi Video</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Tautan YouTube URL *</label>
                        <input type="url" name="youtube_url" id="youtubeUrl" class="form-control" required value="{{ old('youtube_url', $video->youtube_url) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Video *</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $video->title) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Format / Jenis Video *</label>
                        <select name="video_type" class="form-control">
                            <option value="video" {{ old('video_type', $video->video_type) === 'video' ? 'selected' : '' }}>Video Biasa (16:9)</option>
                            <option value="shorts" {{ old('video_type', $video->video_type) === 'shorts' ? 'selected' : '' }}>YouTube Shorts (9:16)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Durasi Video</label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration', $video->duration) }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Deskripsi Video</label>
                        <textarea name="description" rows="5" class="form-control">{{ old('description', $video->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-search" style="color:var(--admin-red)"></i> Optimasi SEO</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $video->meta_title) }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="form-control">{{ old('meta_description', $video->meta_description) }}</textarea>
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
                            <option value="published" {{ old('status', $video->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $video->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori Video</label>
                        <select name="category_id" class="form-control">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $video->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="background: #F8FAFC; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.85rem; margin-bottom: 1.25rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $video->is_featured) ? 'checked' : '' }}>
                            <strong>Featured Video Utama</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="show_homepage" value="1" {{ old('show_homepage', $video->show_homepage) ? 'checked' : '' }}>
                            Tampilkan di Beranda
                        </label>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem;">
                        <i class="fas fa-save"></i> Perbarui Video
                    </button>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="far fa-image" style="color:var(--admin-navy)"></i> Thumbnail</div>
                </div>
                <div class="admin-card-body">
                    <div style="margin-bottom: 1rem; text-align: center;">
                        <img src="{{ $video->thumbnail ?: 'https://img.youtube.com/vi/' . $video->youtube_id . '/hqdefault.jpg' }}" alt="" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 4px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Ganti Custom Thumbnail</label>
                        <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
