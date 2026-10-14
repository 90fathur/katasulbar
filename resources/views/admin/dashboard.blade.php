@extends('admin.layouts.app')

@section('title', 'Dashboard Redaksi & Statistik - KATA SULBAR')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Dashboard Redaksi</h1>
        <p class="admin-page-subtitle">
            Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role_label }}). Berikut ringkasan performa portal Kata Sulbar hari ini.
        </p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('admin.articles.create') }}" class="admin-btn admin-btn-primary">
            <i class="fas fa-plus"></i> Tulis Berita Baru
        </a>
    </div>
</div>

{{-- 1. Metric Cards (Section 15) --}}
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Total Berita</h5>
            <div class="metric-card-val">{{ number_format($totalArticles) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fas fa-newspaper"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Berita Hari Ini</h5>
            <div class="metric-card-val">{{ number_format($articlesToday) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Draft & Review</h5>
            <div class="metric-card-val">{{ number_format($draftArticles) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fas fa-edit"></i>
        </div>
    </div>

    <div class="metric-card accent-red">
        <div class="metric-card-info">
            <h5>Berita Terbit</h5>
            <div class="metric-card-val">{{ number_format($publishedArticles) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Total Pembaca</h5>
            <div class="metric-card-val">{{ number_format($totalViews) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fas fa-eye"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Komentar (Pending)</h5>
            <div class="metric-card-val">{{ number_format($totalComments) }} <span style="font-size:0.875rem; color:var(--admin-red);">({{ $pendingComments }})</span></div>
        </div>
        <div class="metric-card-icon">
            <i class="far fa-comments"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Iklan Aktif</h5>
            <div class="metric-card-val">{{ number_format($activeAds) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fas fa-ad"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-card-info">
            <h5>Video & Shorts</h5>
            <div class="metric-card-val">{{ number_format($totalVideos) }}</div>
        </div>
        <div class="metric-card-icon">
            <i class="fab fa-youtube"></i>
        </div>
    </div>
</div>

{{-- 2. Charts & Analytics Grid --}}
<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;" class="dashboard-analytics-grid">
    <style>
        @media (min-width: 992px) {
            .dashboard-analytics-grid {
                grid-template-columns: 7fr 5fr !important;
            }
        }
    </style>

    {{-- Traffic Chart Box --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fas fa-chart-line" style="color:var(--admin-red)"></i> Tren Pengunjung & Pembaca (7 Hari Terakhir)
            </div>
            <span class="badge badge-info">Realtime Analytics</span>
        </div>
        <div class="admin-card-body">
            <canvas id="visitorChart" style="width: 100%; height: 260px;"></canvas>
        </div>
    </div>

    {{-- Top Categories by Read Count --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fas fa-fire" style="color:var(--admin-red)"></i> Kategori Paling Banyak Dibaca
            </div>
        </div>
        <div class="admin-card-body" style="padding: 0;">
            <div class="admin-table-wrap" style="border: none; border-radius: 0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Berita</th>
                            <th>Total Pembaca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCategories as $cat)
                            <tr>
                                <td>
                                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $cat->color }}; margin-right: 6px;"></span>
                                    <strong>{{ $cat->name }}</strong>
                                </td>
                                <td>{{ $cat->published_articles_count }} berita</td>
                                <td><span class="badge badge-success">{{ number_format($cat->total_views) }} view</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- 3. Top Articles & Activity Log --}}
<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="dashboard-bottom-grid">
    <style>
        @media (min-width: 992px) {
            .dashboard-bottom-grid {
                grid-template-columns: 7fr 5fr !important;
            }
        }
    </style>

    {{-- Berita Terpopuler --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fas fa-trophy" style="color:var(--admin-red)"></i> 5 Berita Terpopuler
            </div>
            <a href="{{ route('admin.articles.index') }}" style="font-size: 0.75rem; color: var(--admin-red); font-weight: 700;">Lihat Semua</a>
        </div>
        <div class="admin-card-body" style="padding: 0;">
            <div class="admin-table-wrap" style="border: none; border-radius: 0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="min-width: 200px;">Judul Berita</th>
                            <th>Kategori</th>
                            <th>Pembaca</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topArticles as $art)
                            <tr>
                                <td style="min-width: 200px;">
                                    <a href="{{ route('news.show', $art->slug) }}" target="_blank" style="font-weight: 600; color: var(--admin-navy);">
                                        {{ Str::limit($art->title, 45) }}
                                    </a>
                                </td>
                                <td><span class="badge badge-info">{{ $art->category ? $art->category->name : 'Sulbar' }}</span></td>
                                <td><strong>{{ number_format($art->views_count) }}</strong></td>
                                <td style="color: var(--admin-text-muted);">{{ $art->published_at ? $art->published_at->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Activity Logs --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fas fa-history" style="color:var(--admin-navy)"></i> Log Aktivitas Redaksi Terkini
            </div>
            <a href="{{ route('admin.activity_logs.index') }}" style="font-size: 0.75rem; color: var(--admin-text-muted); font-weight: 700;">Semua Log</a>
        </div>
        <div class="admin-card-body">
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($recentActivities as $act)
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start; padding-bottom: 0.75rem; border-bottom: 1px solid var(--admin-border);">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(6, 43, 86, 0.1); color: var(--admin-navy); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 700; font-size: 0.8125rem;">
                                {{ $act->user ? $act->user->name : 'Sistem' }} — <span style="color: var(--admin-red);">{{ $act->action }}</span>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-text-muted);">{{ $act->description }}</div>
                            <div style="font-size: 0.7rem; color: #94A3B8; margin-top: 2px;">{{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--admin-text-muted); padding: 1.5rem 0;">Belum ada catatan aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Chart.js CDN for visual chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('visitorChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($days) !!},
            datasets: [{
                label: 'Pembaca / View',
                data: {!! json_encode($viewsData) !!},
                borderColor: '#EF1C25',
                backgroundColor: 'rgba(239, 28, 37, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#062B56',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#E2E8F0' },
                    ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
