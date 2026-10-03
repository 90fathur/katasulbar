@extends('admin.layouts.app')

@section('title', 'Moderasi Komentar - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Moderasi Komentar Pembaca</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Tinjau, setujui, tolak, atau tandai spam pada tanggapan pembaca sebelum ditampilkan di situs berita.
        </p>
    </div>
</div>

{{-- Status Filter Tabs --}}
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
    <a href="{{ route('admin.comments.index', ['status' => 'all']) }}" class="admin-btn {{ $status === 'all' ? 'admin-btn-navy' : 'admin-btn-secondary' }}">
        Semua ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="admin-btn {{ $status === 'pending' ? 'admin-btn-primary' : 'admin-btn-secondary' }}">
        <i class="fas fa-hourglass-half"></i> Menunggu Moderasi ({{ $counts['pending'] }})
    </a>
    <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" class="admin-btn {{ $status === 'approved' ? 'admin-btn-navy' : 'admin-btn-secondary' }}">
        <i class="fas fa-check"></i> Disetujui ({{ $counts['approved'] }})
    </a>
    <a href="{{ route('admin.comments.index', ['status' => 'rejected']) }}" class="admin-btn {{ $status === 'rejected' ? 'admin-btn-navy' : 'admin-btn-secondary' }}">
        <i class="fas fa-times"></i> Ditolak ({{ $counts['rejected'] }})
    </a>
    <a href="{{ route('admin.comments.index', ['status' => 'spam']) }}" class="admin-btn {{ $status === 'spam' ? 'admin-btn-navy' : 'admin-btn-secondary' }}">
        <i class="fas fa-ban"></i> Spam ({{ $counts['spam'] }})
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 180px;">Nama Pengirim</th>
                    <th>Isi Komentar</th>
                    <th>Pada Berita</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th style="width: 180px; text-align: right;">Aksi Moderasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $c)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <img src="{{ $c->avatar_url }}" alt="" style="width: 28px; height: 28px; border-radius: 50%;">
                                <div>
                                    <strong style="color: var(--admin-navy);">{{ $c->name }}</strong>
                                    <div style="font-size: 0.7rem; color: var(--admin-text-muted);">{{ $c->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.8125rem; color: var(--admin-text-main); line-height: 1.45;">
                                {{ $c->content }}
                            </div>
                        </td>
                        <td>
                            @if($c->article)
                                <a href="{{ route('news.show', $c->article->slug) }}" target="_blank" style="font-size: 0.75rem; color: var(--admin-navy); font-weight: 600;">
                                    {{ Str::limit($c->article->title, 35) }}
                                </a>
                            @else
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted);">(Berita dihapus)</span>
                            @endif
                        </td>
                        <td>
                            @if($c->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($c->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($c->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge" style="background:#52525B; color:#fff;">Spam</span>
                            @endif
                        </td>
                        <td style="color: var(--admin-text-muted); font-size: 0.75rem; white-space: nowrap;">
                            {{ $c->created_at->diffForHumans() }}
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.25rem;">
                                @if($c->status !== 'approved')
                                    <form action="{{ route('admin.comments.status', [$c->id, 'approved']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="admin-btn admin-btn-sm" style="background: #DCFCE7; color: #166534;" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($c->status !== 'rejected')
                                    <form action="{{ route('admin.comments.status', [$c->id, 'rejected']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="admin-btn admin-btn-sm" style="background: #FEF3C7; color: #92400E;" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($c->status !== 'spam')
                                    <form action="{{ route('admin.comments.status', [$c->id, 'spam']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="admin-btn admin-btn-sm" style="background: #E2E8F0; color: #334155;" title="Tandai Spam">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.comments.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--admin-text-muted); padding: 2.5rem;">Tidak ada komentar dalam antrean.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem; display: flex; justify-content: center;">
        {{ $comments->links() }}
    </div>
</div>
@endsection
