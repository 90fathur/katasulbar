@extends('admin.layouts.app')

@section('title', 'Manajemen Iklan & Banner - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Manajemen Iklan & Banner</h1>
        <p class="admin-page-subtitle">
            Atur penempatan slot banner horizontal, iklan sidebar 300x600, iklan dalam artikel, dan pantau tracking impresi/klik.
        </p>
    </div>
    <a href="{{ route('admin.advertisements.create') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> Pasang Iklan Baru
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
                    <th style="min-width: 180px;">Judul Iklan</th>
                    <th style="min-width: 140px;">Posisi Slot</th>
                    <th style="min-width: 90px;">Jenis Iklan</th>
                    <th style="min-width: 100px;">Impresi (View)</th>
                    <th style="min-width: 70px;">Klik</th>
                    <th style="min-width: 70px;">CTR</th>
                    <th style="min-width: 80px;">Status</th>
                    <th style="width: 100px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ads as $ad)
                    @php
                        $ctr = $ad->impressions_count > 0 ? round(($ad->clicks_count / $ad->impressions_count) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td>
                            <strong style="color: var(--admin-navy);">{{ $ad->title }}</strong>
                            @if($ad->target_url)
                                <div style="font-size: 0.7rem; color: var(--admin-text-muted);">
                                    Target: <a href="{{ $ad->target_url }}" target="_blank" style="color: var(--admin-red);">{{ Str::limit($ad->target_url, 40) }}</a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($ad->position === 'horizontal_top')
                                <span class="badge badge-info">Top Banner 728×90</span>
                            @elseif($ad->position === 'sidebar_right')
                                <span class="badge" style="background: var(--admin-navy); color: #fff;">Sidebar 300×600</span>
                            @elseif($ad->position === 'home_bottom')
                                <span class="badge badge-success">Bottom 970×250</span>
                            @elseif($ad->position === 'in_article')
                                <span class="badge badge-warning">Dalam Artikel (P:{{ $ad->paragraph_position ?? 3 }})</span>
                            @else
                                <span class="badge badge-info">{{ $ad->position }}</span>
                            @endif
                        </td>
                        <td>
                            @if($ad->ad_type === 'image')
                                <span class="badge badge-info"><i class="far fa-image"></i> Gambar Banner</span>
                            @else
                                <span class="badge badge-warning"><i class="fas fa-code"></i> Script / HTML</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($ad->impressions_count) }}</strong></td>
                        <td><strong>{{ number_format($ad->clicks_count) }}</strong></td>
                        <td><span class="badge badge-success">{{ $ctr }}%</span></td>
                        <td>
                            @if($ad->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('admin.advertisements.edit', $ad->id) }}" class="admin-btn admin-btn-secondary admin-btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.advertisements.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Hapus iklan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--admin-text-muted); padding: 2rem;">Belum ada iklan terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
