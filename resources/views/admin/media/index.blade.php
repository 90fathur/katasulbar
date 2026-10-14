@extends('admin.layouts.app')

@section('title', 'Media Library - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Media Library</h1>
        <p class="admin-page-subtitle">
            Pusat penyimpanan aset foto, grafis, dokumen PDF, dan file multimedia liputan berita.
        </p>
    </div>
</div>

{{-- Upload Card --}}
<div class="admin-card" style="margin-bottom: 0.85rem;">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-cloud-upload-alt" style="color:var(--admin-red)"></i> Unggah File Baru</div>
    </div>
    <div class="admin-card-body" style="padding: 0.65rem 0.85rem;">
        <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="admin-filter-form">
            @csrf
            <div class="admin-filter-search">
                <input type="file" name="files[]" multiple required class="form-control" accept="image/*,video/*,application/pdf" style="padding: 0.15rem 0.4rem; height: 28px;">
            </div>
            <div class="admin-filter-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-upload"></i> Unggah File
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Filter Card --}}
<div class="admin-card" style="margin-bottom: 0.85rem;">
    <div class="admin-card-body" style="padding: 0.65rem 0.85rem;">
        <form action="{{ route('admin.media.index') }}" method="GET" class="admin-filter-form">
            <div class="admin-filter-search">
                <input type="text" name="search" class="form-control" placeholder="Cari nama file media..." value="{{ $search }}">
            </div>
            <div class="admin-filter-select">
                <select name="type" class="form-control">
                    <option value="">Semua Format</option>
                    <option value="image" {{ $type === 'image' ? 'selected' : '' }}>Foto / Gambar</option>
                    <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Video</option>
                    <option value="document" {{ $type === 'document' ? 'selected' : '' }}>Dokumen PDF</option>
                </select>
            </div>
            <div class="admin-filter-actions">
                <button type="submit" class="admin-btn admin-btn-navy"><i class="fas fa-filter"></i> Filter</button>
                @if($search || $type)
                    <a href="{{ route('admin.media.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Media Grid --}}
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    @forelse($mediaItems as $media)
        <div style="background: var(--admin-card-bg); border: 1px solid var(--admin-border); border-radius: 6px; overflow: hidden; display: flex; flex-direction: column;">
            <div style="aspect-ratio: 1 / 1; background: #F8FAFC; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                @if(str_starts_with($media->mime_type, 'image/'))
                    <img src="{{ $media->url }}" alt="{{ $media->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @elseif(str_starts_with($media->mime_type, 'video/'))
                    <i class="fas fa-file-video" style="font-size: 3rem; color: var(--admin-navy);"></i>
                @else
                    <i class="fas fa-file-pdf" style="font-size: 3rem; color: var(--admin-red);"></i>
                @endif
            </div>
            <div style="padding: 0.75rem; flex: 1; display: flex; flex-direction: column;">
                <div style="font-size: 0.75rem; font-weight: 700; color: var(--admin-navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $media->file_name }}">
                    {{ $media->file_name }}
                </div>
                <div style="font-size: 0.7rem; color: var(--admin-text-muted); margin-top: 2px;">
                    {{ $media->formatted_size }} • {{ $media->created_at->format('d/m/Y') }}
                </div>
                <div style="margin-top: auto; padding-top: 0.6rem; display: flex; gap: 0.35rem; justify-content: space-between;">
                    <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm" onclick="copyMediaUrl('{{ $media->url }}', this)" title="Salin URL">
                        <i class="far fa-copy"></i>
                    </button>
                    <form action="{{ route('admin.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Hapus media ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--admin-card-bg); border-radius: 6px; border: 1px solid var(--admin-border); color: var(--admin-text-muted);">
            <i class="far fa-folder-open" style="font-size: 2.5rem; margin-bottom: 0.75rem; display: block;"></i>
            Belum ada file media yang diunggah.
        </div>
    @endforelse
</div>

<div style="display: flex; justify-content: center;">
    {{ $mediaItems->links() }}
</div>

@push('scripts')
<script>
function copyMediaUrl(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => { btn.innerHTML = orig; }, 1500);
    });
}
</script>
@endpush
@endsection
