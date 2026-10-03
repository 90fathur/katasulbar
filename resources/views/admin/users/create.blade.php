@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Tambah Pengguna Baru</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Tambahkan anggota tim redaksi, reporter lapangan, atau kontributor berita.
        </p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="admin-card" style="max-width: 650px;">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-user-plus" style="color:var(--admin-red)"></i> Formulir Anggota Redaksi</div>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Muhammad Firdaus, S.I.Kom" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email *</label>
                <input type="email" name="email" class="form-control" required placeholder="nama@halosulbar.com" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi Akun *</label>
                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Peran / Role *</label>
                    <select name="role" class="form-control" required>
                        <option value="reporter" {{ old('role') === 'reporter' ? 'selected' : '' }}>Reporter (Tulis Berita)</option>
                        <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor (Review & Publish)</option>
                        <option value="pemred" {{ old('role') === 'pemred' ? 'selected' : '' }}>Pemimpin Redaksi</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                        <option value="kontributor" {{ old('role') === 'kontributor' ? 'selected' : '' }}>Kontributor Luar</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Akun *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" class="form-control" placeholder="08..." value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Profil Singkat / Bio</label>
                <textarea name="bio" rows="3" class="form-control" placeholder="Biografi singkat yang tampil pada box penulis di detail artikel...">{{ old('bio') }}</textarea>
            </div>

            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.65rem 1.5rem;">
                <i class="fas fa-save"></i> Simpan Pengguna
            </button>
        </form>
    </div>
</div>
@endsection
