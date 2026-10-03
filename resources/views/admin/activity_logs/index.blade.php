@extends('admin.layouts.app')

@section('title', 'Log Aktivitas Redaksi - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Log Aktivitas Sistem</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Catatan jejak audit aktivitas penulisan, pengubahan, penerbitan, dan moderasi konten.
        </p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 160px;">Pengguna</th>
                    <th>Aksi</th>
                    <th>Keterangan Aktivitas</th>
                    <th>Alamat IP</th>
                    <th>Waktu Kejadian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <strong style="color: var(--admin-navy);">
                                {{ $log->user ? $log->user->name : 'Sistem' }}
                            </strong>
                            <div style="font-size: 0.7rem; color: var(--admin-text-muted);">
                                {{ $log->user ? $log->user->role_label : '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(6, 43, 86, 0.1); color: var(--admin-navy); font-weight: 700;">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td style="color: var(--admin-text-main);">{{ $log->description }}</td>
                        <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                        <td style="color: var(--admin-text-muted); font-size: 0.75rem; white-space: nowrap;">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--admin-text-muted); padding: 2.5rem;">Belum ada catatan aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem; display: flex; justify-content: center;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
