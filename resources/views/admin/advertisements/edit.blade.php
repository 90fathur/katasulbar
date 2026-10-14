@extends('admin.layouts.app')

@section('title', 'Edit Iklan: ' . $ad->title . ' - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Edit Iklan</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Perbarui parameter penempatan, gambar banner, dan periode tayang iklan.
        </p>
    </div>
    <a href="{{ route('admin.advertisements.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="admin-card" style="max-width: 750px;">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-edit" style="color:var(--admin-red)"></i> Formulir Edit Iklan</div>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.advertisements.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama / Judul Iklan *</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title', $ad->title) }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Posisi Penempatan *</label>
                    <select name="position" class="form-control" required>
                        <option value="horizontal_top" {{ old('position', $ad->position) === 'horizontal_top' ? 'selected' : '' }}>Horizontal Atas (728 × 90)</option>
                        <option value="sidebar_right" {{ old('position', $ad->position) === 'sidebar_right' ? 'selected' : '' }}>Sidebar Kanan (300 × 600)</option>
                        <option value="home_bottom" {{ old('position', $ad->position) === 'home_bottom' ? 'selected' : '' }}>Bawah Beranda (970 × 100)</option>
                        <option value="in_article" {{ old('position', $ad->position) === 'in_article' ? 'selected' : '' }}>Dalam Artikel Berita</option>
                        <option value="article_middle" {{ old('position', $ad->position) === 'article_middle' ? 'selected' : '' }}>Tengah Halaman (728 × 90)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Iklan *</label>
                    <select name="ad_type" id="adType" class="form-control" required>
                        <option value="image" {{ old('ad_type', $ad->ad_type) === 'image' ? 'selected' : '' }}>Gambar / Banner</option>
                        <option value="code" {{ old('ad_type', $ad->ad_type) === 'code' ? 'selected' : '' }}>Kode Script / HTML / Google AdSense</option>
                    </select>
                </div>
            </div>

            <div id="imageFields" style="{{ $ad->ad_type === 'code' ? 'display:none;' : '' }}">
                @if($ad->image_url)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ $ad->image_url }}" alt="" style="max-height: 120px; border-radius: 4px; border: 1px solid var(--admin-border);">
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Ganti File Banner</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label class="form-label">Atau URL Gambar</label>
                    <input type="text" name="image_url" class="form-control" value="{{ old('image_url', $ad->image_url) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Target URL (Ketika Diklik)</label>
                    <input type="url" name="target_url" class="form-control" value="{{ old('target_url', $ad->target_url) }}">
                </div>
            </div>

            <div id="codeFields" style="{{ $ad->ad_type === 'code' ? '' : 'display:none;' }}">
                <div class="form-group">
                    <label class="form-label">Kode Iklan (HTML / JavaScript)</label>
                    <textarea name="ad_code" rows="4" class="form-control">{{ old('ad_code', $ad->ad_code) }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tampil Setelah Paragraf Ke- (Khusus Dalam Artikel)</label>
                <select name="paragraph_position" class="form-control">
                    <option value="3" {{ old('paragraph_position', $ad->paragraph_position) == 3 ? 'selected' : '' }}>Setelah Paragraf ke-3</option>
                    <option value="5" {{ old('paragraph_position', $ad->paragraph_position) == 5 ? 'selected' : '' }}>Setelah Paragraf ke-5</option>
                    <option value="7" {{ old('paragraph_position', $ad->paragraph_position) == 7 ? 'selected' : '' }}>Setelah Paragraf ke-7</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Tayang</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $ad->start_date ? $ad->start_date->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Berakhir Tayang</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $ad->end_date ? $ad->end_date->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                    <strong>Aktifkan Iklan</strong>
                </label>
            </div>

            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.65rem 1.5rem;">
                <i class="fas fa-save"></i> Perbarui Iklan
            </button>
        </form>
    </div>
</div>
@endsection
