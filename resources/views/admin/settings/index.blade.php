@extends('admin.layouts.app')

@section('title', 'Pengaturan Website & SEO - KATA SULBAR Admin')

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
                    @php
                        $currentLogoLight = !empty($settings['site_logo']) ? $settings['site_logo'] : asset('assets/images/logo-transparent.png');
                        $currentLogoDark  = !empty($settings['site_logo_dark']) ? $settings['site_logo_dark'] : (!empty($settings['site_logo']) ? $settings['site_logo'] : asset('assets/images/logo-dark-bg.png'));
                        $currentFavicon   = !empty($settings['site_favicon']) ? $settings['site_favicon'] : asset('assets/images/logo-transparent.png');
                    @endphp

                    {{-- Logo Upload & Preview --}}
                    <div class="form-group" style="border: 1px solid var(--admin-border); border-radius: 6px; padding: 0.85rem; background: #FAFBFD; margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-weight: 700; color: var(--admin-navy); margin-bottom: 0.5rem;">
                            <i class="fas fa-image" style="color: var(--admin-red);"></i> Logo Portal Berita
                        </label>

                        <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap;">
                            {{-- Preview Light BG --}}
                            <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 4px; padding: 0.5rem 0.75rem; text-align: center;">
                                <div style="font-size: 0.6rem; color: var(--admin-text-muted); margin-bottom: 0.25rem;">Preview Latar Terang</div>
                                <img id="previewLogoLight" src="{{ $currentLogoLight }}" alt="Logo Light" style="height: 38px; width: auto; object-fit: contain; max-width: 160px;">
                            </div>

                            {{-- Preview Dark BG --}}
                            <div style="background: var(--admin-navy-dark); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; padding: 0.5rem 0.75rem; text-align: center;">
                                <div style="font-size: 0.6rem; color: #94A3B8; margin-bottom: 0.25rem;">Preview Latar Gelap</div>
                                <img id="previewLogoDark" src="{{ $currentLogoDark }}" alt="Logo Dark" style="height: 38px; width: auto; object-fit: contain; max-width: 160px;">
                            </div>
                        </div>

                        <div style="margin-bottom: 0.75rem;">
                            <label class="form-label" style="font-size: 0.7rem; font-weight: 600;">Ganti Logo Utama (PNG, JPG, WebP, SVG)</label>
                            <input type="file" name="site_logo" id="inputSiteLogo" accept="image/*" class="form-control" style="height: 34px; padding: 0.25rem 0.5rem;">
                            <small style="font-size: 0.65rem; color: var(--admin-text-muted); display: block; margin-top: 0.25rem;">
                                Format disarankan: PNG transparan resolusi tinggi (misal: 600x150 px).
                            </small>
                        </div>

                        <div style="margin-bottom: 0.75rem;">
                            <label class="form-label" style="font-size: 0.7rem; font-weight: 600;">Ganti Logo Khusus Latar Gelap / Header Gelap (Opsional)</label>
                            <input type="file" name="site_logo_dark" id="inputSiteLogoDark" accept="image/*" class="form-control" style="height: 34px; padding: 0.25rem 0.5rem;">
                            <small style="font-size: 0.65rem; color: var(--admin-text-muted); display: block; margin-top: 0.25rem;">
                                Jika logo utama teksnya berwarna gelap, unggah varian teks putih di sini agar kontras di sidebar & footer.
                            </small>
                        </div>

                        <div>
                            <label class="form-label" style="font-size: 0.7rem; font-weight: 600;">Ganti Favicon Browser Tab (Opsional, PNG / ICO / SVG)</label>
                            <input type="file" name="site_favicon" accept="image/x-icon,image/png,image/svg+xml" class="form-control" style="height: 34px; padding: 0.25rem 0.5rem;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Media Portal *</label>
                        <input type="text" name="site_name" class="form-control" required value="{{ $settings['site_name'] ?? 'KATA SULBAR' }}">
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

            {{-- Card: Gambar di Samping Logo (Header Panorama / Banner) --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title">
                        <i class="fas fa-image" style="color:var(--admin-red)"></i> Gambar di Samping Logo (Header Panorama / Banner)
                    </div>
                </div>
                <div class="admin-card-body">
                    @php
                        $currentHeaderBg = !empty($settings['header_bg_image']) ? $settings['header_bg_image'] : asset('assets/images/sulbar_panorama.jpg');
                        $isCustomHeaderBg = !empty($settings['header_bg_image']);
                        $currentHeaderHeight = !empty($settings['header_height']) ? (int)$settings['header_height'] : 105;
                        $currentBannerWidth = !empty($settings['header_banner_width']) ? $settings['header_banner_width'] : '60%';
                        $currentBannerOpacity = !empty($settings['header_banner_opacity']) ? $settings['header_banner_opacity'] : '1.0';
                        $currentBannerPos = !empty($settings['header_banner_pos']) ? $settings['header_banner_pos'] : 'center right';
                        $currentBannerUrl = $settings['header_banner_url'] ?? '';
                        $currentBannerTarget = $settings['header_banner_url_target'] ?? '_blank';
                    @endphp

                    {{-- Panduan & Ketentuan Ukuran Resmi --}}
                    <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-left: 4px solid #16A34A; border-radius: 6px; padding: 0.85rem; margin-bottom: 1rem;">
                        <div style="font-weight: 700; color: #166534; font-size: 0.8125rem; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-ruler-combined"></i> Ketentuan & Rekomendasi Ukuran Gambar:
                        </div>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.75rem; color: #14532D; line-height: 1.55;">
                            <li><strong>Rekomendasi Resolusi:</strong> <code>1920 × 250 px</code> s/d <code>1920 × 300 px</code> (Rasio panorama horizontal <code>16:3</code>).</li>
                            <li><strong>Ukuran Minimal:</strong> <code>1200 × 160 px</code> agar gambar tetap tajam pada monitor laptop / desktop.</li>
                            <li><strong>Format File:</strong> JPG, JPEG, PNG, atau WebP (Maksimal 10 MB).</li>
                            <li><strong>Tips Desain Visual:</strong> Gambar ditampilkan utuh tanpa lapisan gradasi buatan dari kode. Anda dapat mengunggah file gambar/banner yang sudah didesain memiliki gradasi atau transparansi menyatu sendiri di sisi kirinya.</li>
                        </ul>
                    </div>

                    {{-- Live Mockup Preview Box --}}
                    <div style="margin-bottom: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: var(--admin-navy);">
                                <i class="fas fa-eye" style="color: #6366F1;"></i> Live Preview Tampilan Header:
                            </span>
                            <span id="headerBgStatusBadge" class="badge" style="background: {{ $isCustomHeaderBg ? '#10B981' : '#64748B' }}; color: #fff; font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 4px;">
                                {{ $isCustomHeaderBg ? 'Gambar Kustom Aktif' : 'Default Panorama Sulbar' }}
                            </span>
                        </div>

                        {{-- Mockup Container --}}
                        <div id="headerMockupContainer" style="
                            position: relative;
                            width: 100%;
                            height: {{ $currentHeaderHeight }}px;
                            background: #FFFFFF;
                            border: 2px dashed #CBD5E1;
                            border-radius: 6px;
                            overflow: hidden;
                            display: flex;
                            align-items: center;
                            padding: 0 1rem;
                            box-shadow: inset 0 2px 4px rgba(0,0,0,0.04);
                            transition: height 0.25s ease;
                        ">
                            {{-- Background image layer (tanpa gradasi penutup) --}}
                            <div id="mockupBgLayer" style="
                                position: absolute;
                                top: 0;
                                right: 0;
                                bottom: 0;
                                width: {{ $currentBannerWidth }};
                                background-image: url('{{ $currentHeaderBg }}');
                                background-size: cover;
                                background-position: {{ $currentBannerPos }};
                                opacity: {{ $currentBannerOpacity }};
                                transition: all 0.25s ease;
                            "></div>

                            {{-- Logo representation on left --}}
                            <div style="position: relative; z-index: 2; display: flex; align-items: center;">
                                <img id="previewLogoInMockup" src="{{ $currentLogoLight }}" alt="Logo" style="height: 48px; width: auto; object-fit: contain;">
                            </div>
                        </div>
                    </div>

                    {{-- Upload Input & Reset Option --}}
                    <div class="form-group" style="border: 1px solid var(--admin-border); border-radius: 6px; padding: 0.85rem; background: #FAFBFD; margin-bottom: 1.25rem;">
                        <label class="form-label" style="font-weight: 700; color: var(--admin-navy); margin-bottom: 0.35rem;">
                            <i class="fas fa-upload" style="color: var(--admin-red);"></i> Unggah Gambar Pengganti Baru
                        </label>
                        <input type="file" name="header_bg_image" id="inputHeaderBgImage" accept="image/png,image/jpeg,image/webp,image/jpg" class="form-control" style="height: 36px; padding: 0.3rem 0.5rem; margin-bottom: 0.5rem;">
                        <small style="font-size: 0.68rem; color: var(--admin-text-muted); display: block; margin-bottom: 0.65rem;">
                            Pilih file gambar panorama baru untuk menggantikan gambar di samping logo. Format: PNG, JPG, JPEG, WebP (Maks 10 MB).
                        </small>

                        @if($isCustomHeaderBg)
                            <div style="padding-top: 0.5rem; border-top: 1px dashed var(--admin-border);">
                                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #DC2626; cursor: pointer;">
                                    <input type="checkbox" name="reset_header_bg" value="1">
                                    <strong>Reset ke Gambar Bawaan (Default Panorama Sulbar)</strong>
                                </label>
                            </div>
                        @endif
                    </div>

                    {{-- Pengaturan Ukuran & Dimensi Tampilan (Kontrol Dinamis) --}}
                    <div style="font-weight: 700; font-size: 0.78rem; color: var(--admin-navy); margin-bottom: 0.65rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fas fa-sliders-h" style="color: #2563EB;"></i> Penentuan Ukuran & Posisi Tampilan:
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; margin-bottom: 0.85rem;">
                        {{-- 1. Tinggi Header --}}
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 600;">
                                Tinggi Header (Pixel)
                            </label>
                            <div style="display: flex; gap: 0.4rem;">
                                <input type="number" name="header_height" id="inputHeaderHeight" class="form-control" min="70" max="250" value="{{ $currentHeaderHeight }}" style="font-size: 0.78rem;">
                                <span style="display: inline-flex; align-items: center; padding: 0 0.5rem; background: #F1F5F9; border: 1px solid var(--admin-border); border-radius: 4px; font-size: 0.72rem; color: #64748B;">px</span>
                            </div>
                            <div style="display: flex; gap: 0.3rem; margin-top: 0.35rem; flex-wrap: wrap;">
                                <button type="button" class="btn-preset-height" data-height="85" style="font-size: 0.62rem; padding: 0.15rem 0.4rem; border: 1px solid #CBD5E1; background: #fff; border-radius: 3px; cursor: pointer;">Ringkas (85px)</button>
                                <button type="button" class="btn-preset-height" data-height="105" style="font-size: 0.62rem; padding: 0.15rem 0.4rem; border: 1px solid #CBD5E1; background: #fff; border-radius: 3px; cursor: pointer;">Standar (105px)</button>
                                <button type="button" class="btn-preset-height" data-height="130" style="font-size: 0.62rem; padding: 0.15rem 0.4rem; border: 1px solid #CBD5E1; background: #fff; border-radius: 3px; cursor: pointer;">Luas (130px)</button>
                            </div>
                        </div>

                        {{-- 2. Lebar Cakupan Gambar --}}
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 600;">
                                Lebar Cakupan Banner
                            </label>
                            <select name="header_banner_width" id="selectBannerWidth" class="form-control" style="font-size: 0.78rem;">
                                <option value="45%" {{ $currentBannerWidth === '45%' ? 'selected' : '' }}>45% - Sisi Kanan Ringkas</option>
                                <option value="55%" {{ $currentBannerWidth === '55%' ? 'selected' : '' }}>55% - Menengah</option>
                                <option value="60%" {{ $currentBannerWidth === '60%' ? 'selected' : '' }}>60% - Standar (Rekomendasi)</option>
                                <option value="70%" {{ $currentBannerWidth === '70%' ? 'selected' : '' }}>70% - Luas & Elegan</option>
                                <option value="80%" {{ $currentBannerWidth === '80%' ? 'selected' : '' }}>80% - Dominan Kanan</option>
                                <option value="100%" {{ $currentBannerWidth === '100%' ? 'selected' : '' }}>100% - Penuh Sepanjang Header</option>
                            </select>
                            <small style="font-size: 0.62rem; color: var(--admin-text-muted); display: block; margin-top: 0.35rem;">
                                Menentukan seberapa lebar area gambar membentang dari kanan ke kiri.
                            </small>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; margin-bottom: 0.85rem;">
                        {{-- 3. Opasitas / Transparansi --}}
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 600;">
                                Kejernihan / Opasitas Foto
                            </label>
                            <select name="header_banner_opacity" id="selectBannerOpacity" class="form-control" style="font-size: 0.78rem;">
                                <option value="1.0" {{ $currentBannerOpacity === '1.0' || $currentBannerOpacity === '1' ? 'selected' : '' }}>100% - Tajam & Penuh (Standar)</option>
                                <option value="0.85" {{ $currentBannerOpacity === '0.85' ? 'selected' : '' }}>85% - Sedikit Halus</option>
                                <option value="0.70" {{ $currentBannerOpacity === '0.70' ? 'selected' : '' }}>70% - Lembut & Elegan</option>
                                <option value="0.50" {{ $currentBannerOpacity === '0.50' ? 'selected' : '' }}>50% - Transparan / Samar</option>
                            </select>
                        </div>

                        {{-- 4. Posisi Fokus Gambar --}}
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 600;">
                                Posisi Fokus Gambar
                            </label>
                            <select name="header_banner_pos" id="selectBannerPos" class="form-control" style="font-size: 0.78rem;">
                                <option value="center right" {{ $currentBannerPos === 'center right' ? 'selected' : '' }}>Kanan Tengah (Rekomendasi Panorama)</option>
                                <option value="center center" {{ $currentBannerPos === 'center center' ? 'selected' : '' }}>Tengah Presisi (Center)</option>
                                <option value="top right" {{ $currentBannerPos === 'top right' ? 'selected' : '' }}>Kanan Atas</option>
                                <option value="bottom right" {{ $currentBannerPos === 'bottom right' ? 'selected' : '' }}>Kanan Bawah</option>
                            </select>
                        </div>
                    </div>

                    {{-- 5. Tautan Banner Opsional --}}
                    <div style="border-top: 1px dashed var(--admin-border); padding-top: 0.75rem; margin-top: 0.75rem;">
                        <div class="form-group" style="margin-bottom: 0.5rem;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 600;">
                                <i class="fas fa-link" style="color: #6366F1;"></i> Tautan Banner / Gambar Samping Logo (Opsional)
                            </label>
                            <div style="display: grid; grid-template-columns: 8fr 4fr; gap: 0.5rem;">
                                <input type="url" name="header_banner_url" placeholder="https://contoh.com/info-wisata" value="{{ $currentBannerUrl }}" class="form-control" style="font-size: 0.78rem;">
                                <select name="header_banner_url_target" class="form-control" style="font-size: 0.78rem;">
                                    <option value="_blank" {{ $currentBannerTarget === '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                                    <option value="_self" {{ $currentBannerTarget === '_self' ? 'selected' : '' }}>Tab Sama (_self)</option>
                                </select>
                            </div>
                            <small style="font-size: 0.62rem; color: var(--admin-text-muted); display: block; margin-top: 0.25rem;">
                                Jika diisi, pengunjung yang mengeklik area gambar samping logo akan diarahkan ke tautan ini.
                            </small>
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

</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputSiteLogo = document.getElementById('inputSiteLogo');
    const inputSiteLogoDark = document.getElementById('inputSiteLogoDark');
    const previewLight = document.getElementById('previewLogoLight');
    const previewDark = document.getElementById('previewLogoDark');
    const previewLogoInMockup = document.getElementById('previewLogoInMockup');

    if (inputSiteLogo) {
        inputSiteLogo.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (previewLight) previewLight.src = evt.target.result;
                    if (previewLogoInMockup) previewLogoInMockup.src = evt.target.result;
                    if (previewDark && (!inputSiteLogoDark || !inputSiteLogoDark.files.length)) {
                        previewDark.src = evt.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (inputSiteLogoDark) {
        inputSiteLogoDark.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (previewDark) previewDark.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Live Preview for Header Panorama / Banner Beside Logo
    const inputHeaderBg = document.getElementById('inputHeaderBgImage');
    const mockupBgLayer = document.getElementById('mockupBgLayer');
    const headerMockupContainer = document.getElementById('headerMockupContainer');
    const inputHeaderHeight = document.getElementById('inputHeaderHeight');
    const selectBannerWidth = document.getElementById('selectBannerWidth');
    const selectBannerOpacity = document.getElementById('selectBannerOpacity');
    const selectBannerPos = document.getElementById('selectBannerPos');
    const badgeStatus = document.getElementById('headerBgStatusBadge');

    if (inputHeaderBg && mockupBgLayer) {
        inputHeaderBg.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    mockupBgLayer.style.backgroundImage = 'url(' + evt.target.result + ')';
                    if (badgeStatus) {
                        badgeStatus.textContent = 'Gambar Baru Dipilih (Belum Disimpan)';
                        badgeStatus.style.background = '#F59E0B';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (inputHeaderHeight && headerMockupContainer) {
        inputHeaderHeight.addEventListener('input', function() {
            const h = Math.max(60, parseInt(this.value) || 105);
            headerMockupContainer.style.height = h + 'px';
        });

        document.querySelectorAll('.btn-preset-height').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const h = this.getAttribute('data-height');
                inputHeaderHeight.value = h;
                headerMockupContainer.style.height = h + 'px';
            });
        });
    }

    if (selectBannerWidth && mockupBgLayer) {
        selectBannerWidth.addEventListener('change', function() {
            mockupBgLayer.style.width = this.value;
        });
    }

    if (selectBannerOpacity && mockupBgLayer) {
        selectBannerOpacity.addEventListener('change', function() {
            mockupBgLayer.style.opacity = this.value;
        });
    }

    if (selectBannerPos && mockupBgLayer) {
        selectBannerPos.addEventListener('change', function() {
            mockupBgLayer.style.backgroundPosition = this.value;
        });
    }
});
</script>
@endpush
@endsection
