@extends('admin.layouts.app')

@section('title', 'Manajemen Tag - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tag / Topik Berita</h1>
        <p class="admin-page-subtitle">
            Kelola kata kunci penanda topik berita untuk memudahkan pembaca menemukan isu terkait.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="tags-admin-grid">
    <style>
        @media (min-width: 992px) {
            .tags-admin-grid {
                grid-template-columns: 4fr 8fr !important;
            }
        }
    </style>

    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-tag" style="color:var(--admin-red)"></i> Tambah Tag Baru</div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Tag *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Mamuju Tengah" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug (Opsional)</label>
                        <input type="text" name="slug" class="form-control" placeholder="mamuju-tengah" value="{{ old('slug') }}">
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-save"></i> Simpan Tag
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-tags" style="color:var(--admin-navy)"></i> Daftar Tag Terdaftar ({{ $tags->total() }})</div>
            </div>
            <div class="admin-table-wrap">
                <div class="table-scroll-hint">
                    <span><i class="fas fa-arrows-alt-h"></i> Geser horizontal untuk melihat tabel</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="min-width: 140px;">Nama Tag</th>
                            <th style="min-width: 110px;">Slug</th>
                            <th style="min-width: 100px;">Penggunaan Berita</th>
                            <th style="width: 80px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $t)
                            <tr>
                                <td><strong>#{{ $t->name }}</strong></td>
                                <td><code>{{ $t->slug }}</code></td>
                                <td><span class="badge badge-info">{{ $t->articles_count }} artikel</span></td>
                                <td style="text-align: right;">
                                    <form action="{{ route('admin.tags.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tag ini?')" style="display: inline;">
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
                                <td colspan="4" style="text-align: center; color: var(--admin-text-muted); padding: 2rem;">Belum ada tag terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem; display: flex; justify-content: center;">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
