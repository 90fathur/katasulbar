@extends('admin.layouts.app')

@section('title', 'Pengaturan Website & SEO - HALO SULBAR Admin')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Pengaturan Website & SEO</h1>
        <p class="admin-page-subtitle">
            Konfigurasi identitas portal berita, kontak redaksi, media sosial, dan meta SEO.
        </p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="settings-grid">
        <style>
            @media (min-width: 992px) {
                .settings-grid {
                    grid-template-columns: 6fr 6fr !important;
                }
            }
        </style>

        {{-- Column 1: Branding & Info --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-globe" style="color:var(--admin-red)"></i> Identitas Portal & Branding</div>
                </div>
                <div class="admin-card-body">
                    {{-- Logo Upload & Preview --}}
                    <div class="form-group" style="border: 1px solid var(--admin-border); border-radius: 6px; padding: 0.75rem; background: #FAFBFD; margin-bottom: 1rem;">
                        <label class="form-label" style="font-weight: 700; color: var(--admin-navy); margin-bottom: 0.5rem;">
                            <i class="fas fa-image" style="color: var(--admin-red);"></i> Logo Portal Berita
                        </label>

                        <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap;">
                            {{-- Preview Light BG --}}
                            <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.5rem 0.75rem; text-align: center;">
                                <div style="font-size: 0.6rem; color: var(--admin-text-muted); margin-bottom: 0.25rem;">Preview Latar Terang</div>
                                <img src="{{ asset('assets/images/logo-transparent.png') }}?v={{ time() }}" alt="Logo Light" style="height: 38px; width: auto; object-fit: contain; max-width: 150px;">
                            </div>

                            {{-- Preview Dark BG --}}
                            <div style="background: var(--admin-navy-dark); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; padding: 0.5rem 0.75rem; text-align: center;">
                                <div style="font-size: 0.6rem; color: #94A3B8; margin-bottom: 0.25rem;">Preview Latar Gelap</div>
                                <img src="{{ asset('assets/images/logo-dark-bg.png') }}?v={{ time() }}" alt="Logo Dark" style="height: 38px; width: auto; object-fit: contain; max-width: 150px;">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="font-size: 0.65rem;">Ganti Logo Baru (PNG, JPG, WebP)</label>
                            <input type="file" name="site_logo" accept="image/*" class="form-control" style="height: 32px; padding: 0.2rem 0.5rem;">
                            <small style="font-size: 0.625rem; color: var(--admin-text-muted); display: block; margin-top: 0.25rem;">
                                Sistem akan otomatis meng-crop dan menghasilkan varian transparan serta varian mode gelap secara otomatis.
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Media Portal *</label>
                        <input type="text" name="site_name" class="form-control" required value="{{ $settings['site_name'] ?? 'HALO SULBAR' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tagline Resmi *</label>
                        <input type="text" name="site_tagline" class="form-control" required value="{{ $settings['site_tagline'] ?? 'Suara Sulbar, Untuk Semua' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi Situs (Meta Description Default)</label>
                        <textarea name="site_description" rows="3" class="form-control">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Kunci Utama (Meta Keywords)</label>
                        <input type="text" name="site_keywords" class="form-control" value="{{ $settings['site_keywords'] ?? '' }}">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Label Lokasi Top Bar</label>
                            <input type="text" name="location_label" class="form-control" value="{{ $settings['location_label'] ?? 'Mamuju, Sulawesi Barat' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Label Cuaca Top Bar</label>
                            <input type="text" name="weather_label" class="form-control" value="{{ $settings['weather_label'] ?? '28°C' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-sliders-h" style="color:var(--admin-navy)"></i> Konfigurasi Fitur & Interaksi</div>
                </div>
                <div class="admin-card-body">
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="enable_dark_mode" value="1" {{ ($settings['enable_dark_mode'] ?? '1') === '1' ? 'checked' : '' }}>
                            <strong>Aktifkan Fitur Dark Mode (Mode Gelap)</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="enable_comments" value="1" {{ ($settings['enable_comments'] ?? '1') === '1' ? 'checked' : '' }}>
                            <strong>Aktifkan Kolom Komentar Publik</strong>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" name="auto_approve_comments" value="1" {{ ($settings['auto_approve_comments'] ?? '0') === '1' ? 'checked' : '' }}>
                            Terbitkan Komentar Otomatis (Tanpa Moderasi Manual)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Column 2: Social Media & Contacts --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-share-alt" style="color:var(--admin-navy)"></i> Tautan Media Sosial Resmi</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-facebook-f" style="color:#1877F2; width:16px;"></i> Facebook Page</label>
                        <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-instagram" style="color:#E4405F; width:16px;"></i> Instagram</label>
                        <input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-youtube" style="color:#FF0000; width:16px;"></i> Channel YouTube</label>
                        <input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-tiktok" style="color:#000000; width:16px;"></i> Akun TikTok</label>
                        <input type="url" name="tiktok_url" class="form-control" value="{{ $settings['tiktok_url'] ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-whatsapp" style="color:#25D366; width:16px;"></i> Saluran WhatsApp / Kontak</label>
                        <input type="url" name="whatsapp_channel" class="form-control" value="{{ $settings['whatsapp_channel'] ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fas fa-building" style="color:var(--admin-red)"></i> Kontak Kantor Redaksi</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Alamat Email Redaksi</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'redaksi@halosulbar.com' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon / Redaksi</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '+62 811-4200-8899' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Kantor Redaksi</label>
                        <textarea name="contact_address" rows="2" class="form-control">{{ $settings['contact_address'] ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Teks Tentang di Footer</label>
                        <textarea name="footer_about" rows="3" class="form-control">{{ $settings['footer_about'] ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.65rem;">
                        <i class="fas fa-save"></i> Simpan Seluruh Pengaturan
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
