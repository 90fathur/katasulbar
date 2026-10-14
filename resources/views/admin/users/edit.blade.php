@extends('admin.layouts.app')

@section('title', 'Edit Pengguna: ' . $user->name . ' - KATA SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Edit Pengguna</h1>
        <p style="font-size: 0.8125rem; color: var(--admin-text-muted);">
            Perbarui data akun pengguna dan hak akses peran tim redaksi.
        </p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="admin-card" style="max-width: 650px;">
    <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-user-edit" style="color:var(--admin-red)"></i> Formulir Edit Anggota</div>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email *</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Peran / Role *</label>
                    <select name="role" class="form-control" required>
                        <option value="reporter" {{ old('role', $user->role) === 'reporter' ? 'selected' : '' }}>Reporter (Tulis Berita)</option>
                        <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor (Review & Publish)</option>
                        <option value="pemred" {{ old('role', $user->role) === 'pemred' ? 'selected' : '' }}>Pemimpin Redaksi</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                        <option value="kontributor" {{ old('role', $user->role) === 'kontributor' ? 'selected' : '' }}>Kontributor Luar</option>
                        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Akun *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Profil Singkat / Bio</label>
                <textarea name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.65rem 1.5rem;">
                <i class="fas fa-save"></i> Perbarui Akun
            </button>
        </form>
    </div>
</div>
@endsection
