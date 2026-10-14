@extends('admin.layouts.app')

@section('title', 'Kelola Halaman Redaksi, Tentang & Kontak - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Halaman Redaksi, Tentang Kami & Kontak</h1>
        <p class="admin-page-subtitle">
            Kelola isi konten susunan redaksi, profil tentang media, informasi kontak, dan halaman kebijakan publik secara dinamis.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.pages.create') }}" class="admin-btn admin-btn-primary">
            <i class="fas fa-plus"></i> Tambah Halaman Baru
        </a>
    </div>
</div>

{{-- Quick Access Cards for Core 3 Pages --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    {{-- Card 1: Struktur Redaksi --}}
    @php $redaksiPage = $corePages['redaksi'] ?? null; @endphp
    <div class="admin-card" style="border-left: 4px solid var(--admin-red); margin-bottom: 0;">
        <div class="admin-card-body" style="padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <div>
                    <span class="badge badge-danger" style="font-size: 0.65rem; text-transform: uppercase; margin-bottom: 0.35rem;">Struktur Organisasi</span>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--admin-navy); margin: 0;">Struktur Redaksi</h3>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(239, 28, 37, 0.1); color: var(--admin-red); display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <p style="font-size: 0.75rem; color: var(--admin-text-muted); margin-bottom: 1rem; line-height: 1.4;">
                Susunan Pemimpin Redaksi, Redaktur Pelaksana, Tim Wartawan 6 Kabupaten, dan Multimedia.
            </p>
            <div style="display: flex; gap: 0.5rem;">
                @if($redaksiPage)
                    <a href="{{ route('admin.pages.edit', $redaksiPage->id) }}" class="admin-btn admin-btn-primary" style="flex: 1; justify-content: center; font-size: 0.75rem; padding: 0.35rem 0.6rem;">
                        <i class="fas fa-edit"></i> Edit Redaksi
                    </a>
                    <a href="{{ route('page.redaksi') }}" target="_blank" class="admin-btn admin-btn-navy" style="font-size: 0.75rem; padding: 0.35rem 0.6rem;" title="Lihat di Web">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Card 2: Tentang Kami --}}
    @php $tentangPage = $corePages['tentang-kami'] ?? null; @endphp
    <div class="admin-card" style="border-left: 4px solid var(--admin-navy); margin-bottom: 0;">
        <div class="admin-card-body" style="padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <div>
                    <span class="badge badge-info" style="font-size: 0.65rem; text-transform: uppercase; margin-bottom: 0.35rem;">Profil Perusahaan</span>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--admin-navy); margin: 0;">Tentang Kami</h3>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(6, 43, 86, 0.1); color: var(--admin-navy); display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                    <i class="fas fa-info-circle"></i>
                </div>
            </div>
            <p style="font-size: 0.75rem; color: var(--admin-text-muted); margin-bottom: 1rem; line-height: 1.4;">
                Visi misi, tagline media, prinsip jurnalisme, dan latar belakang berdirinya KATA SULBAR.
            </p>
            <div style="display: flex; gap: 0.5rem;">
                @if($tentangPage)
                    <a href="{{ route('admin.pages.edit', $tentangPage->id) }}" class="admin-btn admin-btn-primary" style="flex: 1; justify-content: center; font-size: 0.75rem; padding: 0.35rem 0.6rem;">
                        <i class="fas fa-edit"></i> Edit Tentang Kami
                    </a>
                    <a href="{{ route('page.tentang') }}" target="_blank" class="admin-btn admin-btn-navy" style="font-size: 0.75rem; padding: 0.35rem 0.6rem;" title="Lihat di Web">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Card 3: Kontak & Iklan --}}
    @php $kontakPage = $corePages['kontak'] ?? null; @endphp
    <div class="admin-card" style="border-left: 4px solid #10B981; margin-bottom: 0;">
        <div class="admin-card-body" style="padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <div>
                    <span class="badge badge-success" style="font-size: 0.65rem; text-transform: uppercase; margin-bottom: 0.35rem;">Komunikasi Publik</span>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--admin-navy); margin: 0;">Kontak & Kerja Sama</h3>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(16, 185, 129, 0.1); color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                    <i class="fas fa-address-book"></i>
                </div>
            </div>
            <p style="font-size: 0.75rem; color: var(--admin-text-muted); margin-bottom: 1rem; line-height: 1.4;">
                Alamat kantor redaksi, nomor telepon WhatsApp, email resmi, dan informasi tarif kerja sama iklan.
            </p>
            <div style="display: flex; gap: 0.5rem;">
                @if($kontakPage)
                    <a href="{{ route('admin.pages.edit', $kontakPage->id) }}" class="admin-btn admin-btn-primary" style="flex: 1; justify-content: center; font-size: 0.75rem; padding: 0.35rem 0.6rem;">
                        <i class="fas fa-edit"></i> Edit Kontak & Iklan
                    </a>
                    <a href="{{ route('page.kontak') }}" target="_blank" class="admin-btn admin-btn-navy" style="font-size: 0.75rem; padding: 0.35rem 0.6rem;" title="Lihat di Web">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Table of All Pages --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fas fa-file-alt" style="color:var(--admin-red)"></i> Seluruh Daftar Halaman Statis & Informasi Publik
        </div>
    </div>
    <div class="admin-card-body" style="padding: 0;">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul Halaman</th>
                        <th>URL / Tautan Publik</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 150px;">Terakhir Diubah</th>
                        <th style="width: 130px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $index => $page)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong style="font-size: 0.8125rem; color: var(--admin-navy);">{{ $page->title }}</strong>
                                @if(in_array($page->slug, ['redaksi', 'tentang-kami', 'kontak']))
                                    <span class="badge badge-danger" style="font-size: 0.55rem; margin-left: 0.35rem;">Halaman Utama</span>
                                @endif
                                <div style="font-size: 0.6875rem; color: var(--admin-text-muted); margin-top: 0.15rem;">
                                    {{ Str::limit(strip_tags($page->content), 75) }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ url('/' . $page->slug) }}" target="_blank" style="color: var(--admin-red); font-family: monospace; font-size: 0.75rem;">
                                    /{{ $page->slug }} <i class="fas fa-external-link-alt" style="font-size: 0.65rem;"></i>
                                </a>
                            </td>
                            <td>
                                <label class="admin-switch">
                                    <input type="checkbox" class="toggle-page-status" data-id="{{ $page->id }}" {{ $page->is_active ? 'checked' : '' }}>
                                    <span class="admin-slider"></span>
                                </label>
                                <span class="status-label-{{ $page->id }}" style="font-size: 0.6875rem; margin-left: 0.35rem; color: {{ $page->is_active ? 'var(--admin-green)' : 'var(--admin-text-muted)' }};">
                                    {{ $page->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td style="font-size: 0.75rem; color: var(--admin-text-muted);">
                                {{ $page->updated_at ? $page->updated_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.35rem; justify-content: center;">
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="admin-btn-action" title="Edit Halaman">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ url('/' . $page->slug) }}" target="_blank" class="admin-btn-action" title="Lihat di Web" style="color: var(--admin-navy);">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!in_array($page->slug, ['redaksi', 'tentang-kami', 'kontak']))
                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn-action admin-btn-action-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--admin-text-muted);">
                                Belum ada halaman yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-page-status').forEach(switchInput => {
        switchInput.addEventListener('change', function() {
            const pageId = this.getAttribute('data-id');
            const statusLabel = document.querySelector(`.status-label-${pageId}`);
            
            fetch(`/admin/pages/${pageId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.is_active) {
                        statusLabel.textContent = 'Aktif';
                        statusLabel.style.color = 'var(--admin-green)';
                    } else {
                        statusLabel.textContent = 'Nonaktif';
                        statusLabel.style.color = 'var(--admin-text-muted)';
                    }
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengubah status halaman.');
                this.checked = !this.checked;
            });
        });
    });
});
</script>
@endpush
@endsection
