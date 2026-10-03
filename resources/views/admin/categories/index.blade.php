@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Kategori Berita</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Kelola kategori utama dan subkategori berita yang tampil pada navbar dan beranda.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="categories-admin-grid">
    <style>
        @media (min-width: 992px) {
            .categories-admin-grid {
                grid-template-columns: 4.5fr 7.5fr !important;
            }
        }
    </style>

    {{-- Add Category Form --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-plus-circle" style="color:var(--admin-red)"></i> Tambah Kategori Baru</div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Kategori *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Pemerintahan" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug URL (Opsional)</label>
                        <input type="text" name="slug" class="form-control" placeholder="pemerintahan" value="{{ old('slug') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Warna Label / Aksen</label>
                        <input type="color" name="color" class="form-control" value="{{ old('color', '#062B56') }}" style="height: 38px; padding: 2px;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Icon Font Awesome (Opsional)</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa-landmark" value="{{ old('icon') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL Gambar Visual Card (Opsional)</label>
                        <input type="text" name="image" class="form-control" placeholder="https://..." value="{{ old('image') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Urutan Tampil (Order)</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" rows="2" class="form-control" placeholder="Deskripsi topik kategori">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-save"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Category List --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-list" style="color:var(--admin-navy)"></i> Daftar Kategori ({{ $categories->count() }})</div>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Urutan</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Warna</th>
                            <th>Jumlah Berita</th>
                            <th style="width: 100px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td><span class="badge badge-info">{{ $cat->order }}</span></td>
                                <td>
                                    <strong style="color: var(--admin-navy);">{{ $cat->name }}</strong>
                                    @if($cat->icon)
                                        <i class="fas {{ $cat->icon }}" style="margin-left: 4px; font-size: 0.75rem; color: var(--admin-text-muted);"></i>
                                    @endif
                                </td>
                                <td><code>{{ $cat->slug }}</code></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.35rem;">
                                        <span style="display: inline-block; width: 14px; height: 14px; border-radius: 3px; background: {{ $cat->color }};"></span>
                                        <span style="font-size: 0.75rem;">{{ $cat->color }}</span>
                                    </div>
                                </td>
                                <td><strong>{{ $cat->articles_count }}</strong></td>
                                <td style="text-align: right;">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" {{ $cat->articles_count > 0 ? 'disabled title="Kategori masih memiliki berita"' : '' }}>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
