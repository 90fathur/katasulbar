@extends('admin.layouts.app')

@section('title', 'Manajemen Menu Navigasi & Dropdown - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Menu Navigasi & Dropdown</h1>
        <p class="admin-page-subtitle">
            Atur susunan menu bilah navigasi utama, sub-menu bertingkat (dropdown), badge label, dan tautan rubrik berita secara dinamis.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;" class="menu-admin-grid">
    <style>
        @media (min-width: 992px) {
            .menu-admin-grid {
                grid-template-columns: 4.5fr 7.5fr !important;
            }
        }
        .tree-indent {
            padding-left: 1.5rem !important;
            position: relative;
        }
        .tree-indent::before {
            content: '↳';
            position: absolute;
            left: 0.6rem;
            color: var(--admin-red);
            font-weight: 700;
        }
    </style>

    {{-- Form Column: Add Menu --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="fas fa-plus-circle" style="color:var(--admin-red)"></i> Tambah Menu Navigasi
                </div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.menus.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Menu *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Sulbar, Politik, Pilkada" value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Menu Induk (Jadikan Sub-Menu Dropdown)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- Menu Utama / Level 1 (Tanpa Induk) --</option>
                            @foreach($parentMenus as $p)
                                <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                                    📁 {{ $p->title }} (Jadikan Sub-Menu di dalam {{ $p->title }})
                                </option>
                            @endforeach
                        </select>
                        <small style="font-size: 0.625rem; color: var(--admin-text-muted); display: block; margin-top: 0.25rem;">
                            Pilih menu induk jika item ini ingin dijadikan dropdown bertingkat.
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Tautan *</label>
                        <select name="type" id="menuTypeSelect" class="form-control" onchange="handleMenuTypeChange(this.value)">
                            <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>URL Kustom / Halaman Khusus</option>
                            <option value="category" {{ old('type') === 'category' ? 'selected' : '' }}>Kategori Berita</option>
                        </select>
                    </div>

                    {{-- Category Option --}}
                    <div class="form-group" id="menuCatGroup" style="display: {{ old('type') === 'category' ? 'block' : 'none' }};">
                        <label class="form-label">Pilih Kategori Berita *</label>
                        <select name="category_id" id="menuCatSelect" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-url="{{ route('category.show', $cat->slug, false) }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Custom URL Option --}}
                    <div class="form-group" id="menuUrlGroup" style="display: {{ old('type') === 'category' ? 'none' : 'block' }};">
                        <label class="form-label">Tautan / URL Tujuan *</label>
                        <input type="text" name="url" id="menuUrlInput" class="form-control" placeholder="Contoh: /video atau https://..." value="{{ old('url', '/') }}">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;">
                        <div class="form-group">
                            <label class="form-label">Badge Label (Opsional)</label>
                            <input type="text" name="badge" class="form-control" placeholder="HOT, NEW, LIVE" value="{{ old('badge') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Urutan (Order)</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', $menus->count() + 1) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Target Jendela</label>
                        <select name="target" class="form-control">
                            <option value="_self" {{ old('target') === '_self' ? 'selected' : '' }}>Buka di tab yang sama (_self)</option>
                            <option value="_blank" {{ old('target') === '_blank' ? 'selected' : '' }}>Buka di tab baru (_blank)</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.72rem; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                            <strong>Aktifkan menu ini di bilah navigasi</strong>
                        </label>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-save"></i> Simpan Menu Navigasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- List Column: Active Menus & Dropdowns --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="fas fa-bars-staggered" style="color:var(--admin-navy)"></i> Struktur Menu & Dropdown Navigasi
                </div>
                <span class="badge badge-info">{{ $menus->count() }} Menu Utama</span>
            </div>
            <div class="admin-table-wrap">
                <div class="table-scroll-hint">
                    <span><i class="fas fa-arrows-alt-h"></i> Geser horizontal untuk melihat kolom lengkap</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Urutan</th>
                            <th style="min-width: 180px;">Nama Menu & Tautan</th>
                            <th style="min-width: 110px;">Tipe</th>
                            <th style="min-width: 100px;">Dropdown</th>
                            <th style="width: 70px; text-align: center;">Aktif</th>
                            <th style="width: 90px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr style="background: #FAFBFD;">
                                <td><span class="badge badge-info">{{ $menu->order }}</span></td>
                                <td>
                                    <div style="font-weight: 700; color: var(--admin-navy); display: flex; align-items: center; gap: 0.4rem;">
                                        <span>{{ $menu->title }}</span>
                                        @if($menu->badge)
                                            <span class="badge" style="background: var(--admin-red); color: #fff;">{{ $menu->badge }}</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.65rem; color: var(--admin-text-muted); margin-top: 2px;">
                                        <code>{{ $menu->url }}</code>
                                        @if($menu->target === '_blank')
                                            <span title="Buka di tab baru"><i class="fas fa-external-link-alt" style="font-size: 0.6rem;"></i></span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $menu->type === 'category' ? '#062B56' : '#64748B' }}; color: #fff;">
                                        {{ $menu->type === 'category' ? 'Kategori' : 'Custom' }}
                                    </span>
                                </td>
                                <td>
                                    @if($menu->children->count() > 0)
                                        <span class="badge badge-warning" style="cursor: pointer;" title="Memiliki sub-menu dropdown">
                                            <i class="fas fa-chevron-down"></i> {{ $menu->children->count() }} Sub-Menu
                                        </span>
                                    @else
                                        <span style="font-size: 0.65rem; color: var(--admin-text-muted);">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <label class="switch quick-toggle-switch" title="Toggle Aktif">
                                        <input type="checkbox" onchange="toggleMenuStatus({{ $menu->id }})" {{ $menu->is_active ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 0.3rem;">
                                        <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm" title="Edit Menu" onclick="openEditModal({{ json_encode($menu) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu \'{{ $menu->title }}\' beserta submenunya?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" title="Hapus Menu">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Nested Dropdown Sub-menus --}}
                            @foreach($menu->children as $child)
                                <tr>
                                    <td><span class="badge" style="background:#E2E8F0; color:#475569;">{{ $child->order }}</span></td>
                                    <td class="tree-indent">
                                        <div style="font-weight: 600; color: var(--admin-text-main); display: flex; align-items: center; gap: 0.35rem;">
                                            <span>{{ $child->title }}</span>
                                            @if($child->badge)
                                                <span class="badge" style="background: var(--admin-red); color: #fff;">{{ $child->badge }}</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.625rem; color: var(--admin-text-muted); margin-top: 1px;">
                                            <code>{{ $child->url }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #94A3B8; color: #fff; font-size: 0.55rem;">
                                            {{ $child->type === 'category' ? 'Kategori' : 'Custom' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.625rem; color: var(--admin-text-muted);">Sub di {{ $menu->title }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <label class="switch quick-toggle-switch" title="Toggle Aktif">
                                            <input type="checkbox" onchange="toggleMenuStatus({{ $child->id }})" {{ $child->is_active ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; gap: 0.3rem;">
                                            <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm" title="Edit Sub-Menu" onclick="openEditModal({{ json_encode($child) }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Hapus sub-menu \'{{ $child->title }}\'?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" title="Hapus Sub-Menu">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--admin-text-muted);">
                                    Belum ada menu navigasi yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editMenuModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(3, 23, 48, 0.6); backdrop-filter: blur(2px); z-index: 2000; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #FFFFFF; border-radius: 6px; width: 100%; max-width: 480px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); overflow: hidden;">
        <div class="admin-card-header" style="background: #F8FAFC;">
            <div class="admin-card-title"><i class="fas fa-edit" style="color:var(--admin-navy)"></i> Edit Menu Navigasi</div>
            <button type="button" onclick="closeEditModal()" style="background:none; border:none; font-size: 1.1rem; color: #64748B; cursor: pointer;">&times;</button>
        </div>
        <form id="editMenuForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="admin-card-body" style="padding: 1rem;">
                <div class="form-group">
                    <label class="form-label">Nama Menu *</label>
                    <input type="text" name="title" id="editMenuTitle" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Menu Induk (Dropdown Sub-Menu)</label>
                    <select name="parent_id" id="editMenuParentId" class="form-control">
                        <option value="">-- Menu Utama / Level 1 (Tanpa Induk) --</option>
                        @foreach($parentMenus as $p)
                            <option value="{{ $p->id }}">📁 {{ $p->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Tautan *</label>
                    <select name="type" id="editMenuType" class="form-control" onchange="handleEditMenuTypeChange(this.value)">
                        <option value="custom">URL Kustom / Bebas</option>
                        <option value="category">Kategori Berita</option>
                    </select>
                </div>

                <div class="form-group" id="editMenuCatGroup" style="display: none;">
                    <label class="form-label">Pilih Kategori Berita</label>
                    <select name="category_id" id="editMenuCatId" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="editMenuUrlGroup">
                    <label class="form-label">Tautan / URL Tujuan</label>
                    <input type="text" name="url" id="editMenuUrl" class="form-control">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;">
                    <div class="form-group">
                        <label class="form-label">Badge Label</label>
                        <input type="text" name="badge" id="editMenuBadge" class="form-control" placeholder="HOT, NEW">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Urutan (Order)</label>
                        <input type="number" name="order" id="editMenuOrder" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Target Jendela</label>
                    <select name="target" id="editMenuTarget" class="form-control">
                        <option value="_self">Buka di tab yang sama (_self)</option>
                        <option value="_blank">Buka di tab baru (_blank)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.72rem; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="editMenuActive" value="1">
                        <strong>Menu ini aktif di navigasi</strong>
                    </label>
                </div>
            </div>
            <div style="padding: 0.75rem 1rem; background: #F8FAFC; border-top: 1px solid var(--admin-border); display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="admin-btn admin-btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handleMenuTypeChange(type) {
        const catGroup = document.getElementById('menuCatGroup');
        const urlGroup = document.getElementById('menuUrlGroup');
        if (type === 'category') {
            catGroup.style.display = 'block';
            urlGroup.style.display = 'none';
        } else {
            catGroup.style.display = 'none';
            urlGroup.style.display = 'block';
        }
    }

    function handleEditMenuTypeChange(type) {
        const catGroup = document.getElementById('editMenuCatGroup');
        const urlGroup = document.getElementById('editMenuUrlGroup');
        if (type === 'category') {
            catGroup.style.display = 'block';
            urlGroup.style.display = 'none';
        } else {
            catGroup.style.display = 'none';
            urlGroup.style.display = 'block';
        }
    }

    function openEditModal(menu) {
        const modal = document.getElementById('editMenuModal');
        const form = document.getElementById('editMenuForm');
        form.action = `/admin/menus/${menu.id}`;

        document.getElementById('editMenuTitle').value = menu.title || '';
        document.getElementById('editMenuParentId').value = menu.parent_id || '';
        document.getElementById('editMenuType').value = menu.type || 'custom';
        document.getElementById('editMenuCatId').value = menu.category_id || '';
        document.getElementById('editMenuUrl').value = menu.url || '';
        document.getElementById('editMenuBadge').value = menu.badge || '';
        document.getElementById('editMenuOrder').value = menu.order || 0;
        document.getElementById('editMenuTarget').value = menu.target || '_self';
        document.getElementById('editMenuActive').checked = !!menu.is_active;

        handleEditMenuTypeChange(menu.type || 'custom');

        modal.style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editMenuModal').style.display = 'none';
    }

    function toggleMenuStatus(id) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/admin/menus/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Gagal mengubah status menu');
                location.reload();
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan');
            location.reload();
        });
    }

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endpush
@endsection
