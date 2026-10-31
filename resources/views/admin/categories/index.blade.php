@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Kategori Berita</h1>
        <p class="admin-page-subtitle">
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
        .form-error-msg {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
    </style>

    {{-- Add Category Form --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="fas fa-plus-circle" style="color:var(--admin-red)"></i> Tambah Kategori Baru</div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" id="formAddCategory" onsubmit="const btn = document.getElementById('btnSubmitCategory'); btn.disabled = true; btn.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Menyimpan...';">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Kategori *</label>
                        <input type="text" name="name" id="inputCategoryName" class="form-control @error('name') is-invalid @enderror" required placeholder="Contoh: Pemerintahan" value="{{ old('name') }}" oninput="autoSuggestSlug(this.value)">
                        @error('name')
                            <div class="form-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug URL (Opsional / Otomatis)</label>
                        <input type="text" name="slug" id="inputCategorySlug" class="form-control @error('slug') is-invalid @enderror" placeholder="pemerintahan" value="{{ old('slug') }}">
                        <small style="color: var(--admin-text-muted); font-size: 0.7rem; display: block; margin-top: 2px;">
                            Jika dikosongkan, slug dibuat otomatis dari nama kategori.
                        </small>
                        @error('slug')
                            <div class="form-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
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

                    <button type="submit" id="btnSubmitCategory" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-save"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Category List --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div class="admin-card-title"><i class="fas fa-list" style="color:var(--admin-navy)"></i> Daftar Kategori ({{ $categories->count() }})</div>
                <div>
                    <input type="text" id="categorySearchInput" placeholder="Cari kategori..." class="form-control" style="font-size: 0.75rem; height: 30px; width: 170px; padding: 2px 8px;" onkeyup="filterCategoryTable()">
                </div>
            </div>
            <div class="admin-table-wrap">
                <div class="table-scroll-hint">
                    <span><i class="fas fa-arrows-alt-h"></i> Geser horizontal untuk melihat tabel</span>
                </div>
                <table class="admin-table" id="categoryTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Urutan</th>
                            <th style="min-width: 140px;">Nama Kategori</th>
                            <th style="min-width: 110px;">Slug</th>
                            <th style="min-width: 90px;">Warna</th>
                            <th style="min-width: 90px;">Jumlah Berita</th>
                            <th style="width: 100px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr class="category-row" data-name="{{ strtolower($cat->name) }}" data-slug="{{ strtolower($cat->slug) }}">
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
                                    <div style="display: inline-flex; gap: 4px; justify-content: flex-end;">
                                        <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm" onclick='openEditCategoryModal(@json($cat))' title="Edit Kategori">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" {{ $cat->articles_count > 0 ? 'disabled title="Kategori masih memiliki berita"' : '' }}>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyCategoryRow">
                                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--admin-text-muted);">
                                    Belum ada kategori yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div id="editCategoryModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(3, 23, 48, 0.6); backdrop-filter: blur(2px); z-index: 2000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #FFFFFF; border-radius: 6px; width: 100%; max-width: 480px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); overflow: hidden;">
        <div class="admin-card-header" style="background: #F8FAFC; display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem;">
            <div class="admin-card-title"><i class="fas fa-edit" style="color:var(--admin-navy)"></i> Edit Kategori Berita</div>
            <button type="button" onclick="closeEditCategoryModal()" style="background:none; border:none; font-size: 1.25rem; color: #64748B; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form id="editCategoryForm" method="POST" action="" onsubmit="const btn = document.getElementById('btnSubmitEditCategory'); btn.disabled = true; btn.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Menyimpan...';">
            @csrf
            @method('PUT')
            <div class="admin-card-body" style="padding: 1rem; max-height: calc(85vh - 120px); overflow-y: auto;">
                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" id="editCategoryName" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Slug URL *</label>
                    <input type="text" name="slug" id="editCategorySlug" class="form-control" required>
                    <small style="color: var(--admin-text-muted); font-size: 0.7rem;">Gunakan huruf kecil dan tanda hubung (-), contoh: berita-utama</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Warna Label / Aksen</label>
                    <input type="color" name="color" id="editCategoryColor" class="form-control" style="height: 38px; padding: 2px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Icon Font Awesome (Opsional)</label>
                    <input type="text" name="icon" id="editCategoryIcon" class="form-control" placeholder="fa-landmark">
                </div>

                <div class="form-group">
                    <label class="form-label">URL Gambar Visual Card (Opsional)</label>
                    <input type="text" name="image" id="editCategoryImage" class="form-control" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label class="form-label">Urutan Tampil (Order)</label>
                    <input type="number" name="order" id="editCategoryOrder" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" id="editCategoryDescription" rows="2" class="form-control"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.75rem; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="editCategoryActive" value="1">
                        <strong>Kategori ini aktif di sistem</strong>
                    </label>
                </div>
            </div>
            <div style="padding: 0.75rem 1rem; background: #F8FAFC; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeEditCategoryModal()">Batal</button>
                <button type="submit" id="btnSubmitEditCategory" class="admin-btn admin-btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function autoSuggestSlug(name) {
        const slugInput = document.getElementById('inputCategorySlug');
        // Only auto suggest if user hasn't typed a custom slug
        if (!slugInput.dataset.manual) {
            slugInput.placeholder = name.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .trim()
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '') || 'pemerintahan';
        }
    }

    document.getElementById('inputCategorySlug')?.addEventListener('input', function() {
        this.dataset.manual = this.value.trim() ? 'true' : '';
    });

    function openEditCategoryModal(cat) {
        const modal = document.getElementById('editCategoryModal');
        const form = document.getElementById('editCategoryForm');
        form.action = `/admin/categories/${cat.id}`;

        document.getElementById('editCategoryName').value = cat.name || '';
        document.getElementById('editCategorySlug').value = cat.slug || '';
        document.getElementById('editCategoryColor').value = cat.color || '#062B56';
        document.getElementById('editCategoryIcon').value = cat.icon || '';
        document.getElementById('editCategoryImage').value = cat.image || '';
        document.getElementById('editCategoryOrder').value = cat.order !== undefined ? cat.order : 0;
        document.getElementById('editCategoryDescription').value = cat.description || '';
        document.getElementById('editCategoryActive').checked = cat.is_active !== undefined ? !!cat.is_active : true;

        const btn = document.getElementById('btnSubmitEditCategory');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';

        modal.style.display = 'flex';
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').style.display = 'none';
    }

    function filterCategoryTable() {
        const query = document.getElementById('categorySearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.category-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const slug = row.getAttribute('data-slug') || '';
            if (name.includes(query) || slug.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Close modal on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditCategoryModal();
    });
</script>
@endpush
@endsection
