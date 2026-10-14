@extends('admin.layouts.app')

@section('title', 'Manajemen Breaking News - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Breaking News Ticker</h1>
        <p class="admin-page-subtitle">
            Kelola teks berjalan berita kilat / breaking news di bawah bilah navigasi utama.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="bn-admin-grid">
    <style>
        @media (min-width: 992px) {
            .bn-admin-grid {
                grid-template-columns: 4.5fr 7.5fr !important;
            }
        }
    </style>

    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-bolt" style="color:var(--admin-red)"></i> Tambah Breaking News</div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.breaking-news.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Teks Berita Kilat *</label>
                        <textarea name="title" rows="3" class="form-control" required placeholder="Contoh: Pemprov Sulbar Siapkan Bantuan Tanggap Bencana...">{{ old('title') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tautkan ke Artikel Tertentu</label>
                        <select name="article_id" class="form-control">
                            <option value="">Pilih Berita Terbit (Opsional)</option>
                            @foreach($articles as $a)
                                <option value="{{ $a->id }}">{{ Str::limit($a->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Atau URL Eksternal</label>
                        <input type="text" name="url" class="form-control" placeholder="https://..." value="{{ old('url') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Urutan Tampil (Order)</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <strong>Aktifkan pada Ticker Beranda</strong>
                        </label>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-save"></i> Simpan Breaking News
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-list" style="color:var(--admin-navy)"></i> Daftar Breaking News Aktif ({{ $breakingNews->count() }})</div>
            </div>
            <div class="admin-table-wrap">
                <div class="table-scroll-hint">
                    <span><i class="fas fa-arrows-alt-h"></i> Geser horizontal untuk melihat tabel</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Urutan</th>
                            <th style="min-width: 220px;">Judul Breaking News</th>
                            <th style="min-width: 140px;">Tautan</th>
                            <th style="min-width: 80px;">Status</th>
                            <th style="width: 80px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($breakingNews as $bn)
                            <tr>
                                <td><span class="badge badge-info">{{ $bn->order }}</span></td>
                                <td>
                                    <strong style="color: var(--admin-navy);">{{ $bn->title }}</strong>
                                </td>
                                <td>
                                    <a href="{{ $bn->target_url }}" target="_blank" style="font-size: 0.75rem; color: var(--admin-red);">
                                        <i class="fas fa-external-link-alt"></i> Tautan
                                    </a>
                                </td>
                                <td>
                                    @if($bn->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <form action="{{ route('admin.breaking-news.destroy', $bn->id) }}" method="POST" onsubmit="return confirm('Hapus breaking news ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--admin-text-muted); padding: 2rem;">Belum ada breaking news terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
