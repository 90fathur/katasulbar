@extends('admin.layouts.app')

@section('title', 'Manajemen Video & Shorts - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Galeri Video & YouTube Shorts</h1>
        <p class="admin-page-subtitle">
            Kelola integrasi video YouTube, live streaming, dan video vertikal YouTube Shorts di Kata Sulbar.
        </p>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> Tambah Video Baru
    </a>
</div>

{{-- Filter Card --}}
<div class="admin-card" style="margin-bottom: 0.85rem;">
    <div class="admin-card-body" style="padding: 0.65rem 0.85rem;">
        <form action="{{ route('admin.videos.index') }}" method="GET" class="admin-filter-form">
            <div class="admin-filter-search">
                <input type="text" name="search" class="form-control" placeholder="Cari judul video..." value="{{ $search }}">
            </div>
            <div class="admin-filter-select">
                <select name="type" class="form-control">
                    <option value="">Semua Format</option>
                    <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Video Biasa (16:9)</option>
                    <option value="shorts" {{ $type === 'shorts' ? 'selected' : '' }}>YouTube Shorts (9:16)</option>
                </select>
            </div>
            <div class="admin-filter-actions">
                <button type="submit" class="admin-btn admin-btn-navy">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if($search || $type)
                    <a href="{{ route('admin.videos.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <div class="table-scroll-hint">
            <span><i class="fas fa-arrows-alt-h"></i> Geser horizontal untuk melihat tabel</span>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Thumbnail</th>
                    <th style="min-width: 240px;">Judul Video</th>
                    <th style="min-width: 80px;">Format</th>
                    <th style="min-width: 90px;">Kategori</th>
                    <th style="min-width: 70px;">Durasi</th>
                    <th style="min-width: 80px;">Ditonton</th>
                    <th style="min-width: 80px;">Status</th>
                    <th style="width: 100px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $vid)
                    <tr>
                        <td>
                            <img src="{{ $vid->thumbnail ?: 'https://img.youtube.com/vi/' . $vid->youtube_id . '/hqdefault.jpg' }}" alt="" style="width: 60px; height: {{ $vid->is_short ? '60px' : '40px' }}; border-radius: 4px; object-fit: cover;">
                        </td>
                        <td>
                            <a href="{{ route('video.show', $vid->slug) }}" target="_blank" style="font-weight: 700; color: var(--admin-navy);">
                                {{ $vid->title }}
                            </a>
                            <div style="font-size: 0.7rem; color: var(--admin-text-muted);">
                                ID: <code>{{ $vid->youtube_id }}</code> • {{ $vid->published_at ? $vid->published_at->format('d/m/Y') : 'Draft' }}
                            </div>
                        </td>
                        <td>
                            @if($vid->is_short)
                                <span class="badge" style="background: #FEE2E2; color: var(--admin-red); font-weight: 800;">
                                    <i class="fab fa-youtube"></i> Shorts 9:16
                                </span>
                            @else
                                <span class="badge badge-info">Video 16:9</span>
                            @endif
                        </td>
                        <td>{{ $vid->category ? $vid->category->name : '-' }}</td>
                        <td><strong>{{ $vid->duration }}</strong></td>
                        <td>{{ number_format($vid->views) }}</td>
                        <td>
                            @if($vid->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('admin.videos.edit', $vid->id) }}" class="admin-btn admin-btn-secondary admin-btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.videos.destroy', $vid->id) }}" method="POST" onsubmit="return confirm('Hapus video ini?')">
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
                        <td colspan="8" style="text-align: center; color: var(--admin-text-muted); padding: 2rem;">Tidak ada video ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem; display: flex; justify-content: center;">
        {{ $videos->links() }}
    </div>
</div>
@endsection
