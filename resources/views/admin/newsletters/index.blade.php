@extends('admin.layouts.app')

@section('title', 'Pelanggan Newsletter - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Pelanggan Newsletter</h1>
        <p class="admin-page-subtitle">
            Daftar alamat email pembaca yang berlangganan kabar berita Kata Sulbar.
        </p>
    </div>
    <a href="{{ route('admin.newsletters.export') }}" class="admin-btn admin-btn-navy">
        <i class="fas fa-file-download"></i> Unduh / Export CSV
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <div class="table-scroll-hint">
            <span><i class="fas fa-arrows-alt-h"></i> Geser horizontal untuk melihat tabel</span>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="min-width: 200px;">Alamat Email</th>
                    <th style="min-width: 90px;">Status</th>
                    <th style="min-width: 140px;">Tanggal Mendaftar</th>
                    <th style="width: 80px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $index => $sub)
                    <tr>
                        <td>{{ $subscribers->firstItem() + $index }}</td>
                        <td>
                            <strong style="color: var(--admin-navy);">{{ $sub->email }}</strong>
                        </td>
                        <td>
                            @if($sub->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="color: var(--admin-text-muted); font-size: 0.75rem;">
                            {{ $sub->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('admin.newsletters.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Hapus subscriber ini?')">
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
                        <td colspan="5" style="text-align: center; color: var(--admin-text-muted); padding: 2.5rem;">Belum ada pelanggan newsletter terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem; display: flex; justify-content: center;">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
