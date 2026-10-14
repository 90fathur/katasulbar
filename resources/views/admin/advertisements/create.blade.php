@extends('admin.layouts.app')

@section('title', 'Pasang Iklan Baru - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Pasang Iklan Baru</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Konfigurasi penempatan slot iklan sponsor, banner instansi, atau kode Google Ads.
        </p>
    </div>
    <a href="{{ route('admin.advertisements.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="admin-card" style="max-width: 750px;">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-ad" style="color:var(--admin-red)"></i> Formulir Iklan</div>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama / Judul Iklan *</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Iklan Sponsor Bank Sulbar" value="{{ old('title') }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Posisi Penempatan *</label>
                    <select name="position" id="adPosition" class="form-control" required>
                        <option value="horizontal_top">Horizontal Atas (728 × 90)</option>
                        <option value="sidebar_right">Sidebar Kanan (300 × 600)</option>
                        <option value="home_bottom">Bawah Beranda (970 × 100)</option>
                        <option value="in_article">Dalam Artikel Berita</option>
                        <option value="article_middle">Tengah Halaman (728 × 90)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Iklan *</label>
                    <select name="ad_type" id="adType" class="form-control" required>
                        <option value="image">Gambar / Banner</option>
                        <option value="code">Kode Script / HTML / Google AdSense</option>
                    </select>
                </div>
            </div>

            {{-- Image Box --}}
            <div id="imageFields">
                <div class="form-group">
                    <label class="form-label">Upload File Banner</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label class="form-label">Atau URL Gambar</label>
                    <input type="text" name="image_url" class="form-control" placeholder="https://..." value="{{ old('image_url') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Target URL (Ketika Diklik)</label>
                    <input type="url" name="target_url" class="form-control" placeholder="https://perusahaan.com" value="{{ old('target_url') }}">
                </div>
            </div>

            {{-- Code Box --}}
            <div id="codeFields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Kode Iklan (HTML / JavaScript)</label>
                    <textarea name="ad_code" rows="4" class="form-control" placeholder="<script>...</script>">{{ old('ad_code') }}</textarea>
                </div>
            </div>

            {{-- In-article paragraph option --}}
            <div class="form-group" id="paragraphPositionField">
                <label class="form-label">Tampil Setelah Paragraf Ke- (Khusus Dalam Artikel)</label>
                <select name="paragraph_position" class="form-control">
                    <option value="3">Setelah Paragraf ke-3</option>
                    <option value="5">Setelah Paragraf ke-5</option>
                    <option value="7">Setelah Paragraf ke-7</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Tayang</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Berakhir Tayang</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <strong>Aktifkan Iklan Segera</strong>
                </label>
            </div>

            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.65rem 1.5rem;">
                <i class="fas fa-save"></i> Simpan Iklan
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
const adType = document.getElementById('adType');
const imageFields = document.getElementById('imageFields');
const codeFields = document.getElementById('codeFields');

adType.addEventListener('change', () => {
    if (adType.value === 'code') {
        imageFields.style.display = 'none';
        codeFields.style.display = 'block';
    } else {
        imageFields.style.display = 'block';
        codeFields.style.display = 'none';
    }
});
</script>
@endpush
@endsection
