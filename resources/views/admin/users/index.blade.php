@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna & Role - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Pengguna & Peran Redaksi</h1>
        <p class="admin-page-subtitle">
            Kelola struktur peran hak akses tim redaksi (Super Admin, Administrator, Pemred, Editor, Reporter, Kontributor).
        </p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
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
                    <th style="min-width: 170px;">Nama Pengguna</th>
                    <th style="min-width: 160px;">Email</th>
                    <th style="min-width: 110px;">Role / Peran</th>
                    <th style="min-width: 80px;">Status</th>
                    <th style="min-width: 90px;">Artikel Ditulis</th>
                    <th style="min-width: 90px;">Video Dibuat</th>
                    <th style="width: 100px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <div class="admin-user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong style="color: var(--admin-navy);">{{ $u->name }}</strong>
                                    @if($u->phone)
                                        <div style="font-size: 0.7rem; color: var(--admin-text-muted);"><i class="fas fa-phone"></i> {{ $u->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if($u->role === 'super_admin')
                                <span class="badge" style="background: var(--admin-red); color: #fff;">Super Admin</span>
                            @elseif($u->role === 'admin')
                                <span class="badge" style="background: var(--admin-navy); color: #fff;">Administrator</span>
                            @elseif($u->role === 'pemred')
                                <span class="badge" style="background: #0284C7; color: #fff;">Pemred</span>
                            @elseif($u->role === 'editor')
                                <span class="badge badge-info">Editor</span>
                            @else
                                <span class="badge badge-success">{{ ucfirst($u->role) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($u->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td><strong>{{ $u->articles_count }}</strong> berita</td>
                        <td><strong>{{ $u->videos_count }}</strong> video</td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="admin-btn admin-btn-secondary admin-btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
