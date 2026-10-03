@props(['ad' => null, 'position' => null, 'slotClass' => ''])

@php
    if (!$ad && $position && isset($ads[$position])) {
        $ad = $ads[$position]->first();
    }
@endphp

@if($ad && $ad->is_active)
    <div class="ad-slot-item {{ $slotClass }}">
        @if($ad->ad_type === 'code' && !empty($ad->ad_code))
            {!! $ad->ad_code !!}
        @elseif(!empty($ad->image_url))
            <a href="{{ route('ad.click', $ad->id) }}" target="_blank" rel="noopener noreferrer">
                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="ad-image-responsive">
            </a>
        @else
            {{-- Default Branded Placeholder if no client image uploaded yet --}}
            @if($ad->position === 'horizontal_top')
                <div class="ad-banner-728">
                    <div class="ad-banner-inner">
                        <div class="ad-banner-content">
                            <div class="ad-logo-box">
                                <div class="brand-logo-text">
                                    <span class="halo">HALO</span>
                                    <span class="sulbar">SULBAR</span>
                                </div>
                            </div>
                            <div class="ad-info-text">
                                <h4>SPACE BANNER 728 × 90</h4>
                                <p>Promosikan bisnis, instansi atau kegiatan Anda di sini</p>
                            </div>
                        </div>
                        <a href="{{ route('page.kontak') }}" class="ad-banner-btn">
                            Pasang Iklan <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @elseif($ad->position === 'sidebar_right')
                <div class="ad-sidebar-300">
                    <div class="ad-sidebar-content">
                        <div class="brand-logo-text">
                            <span class="halo" style="font-size: 1.5rem; color: #FFFFFF;">HALO</span>
                            <span class="sulbar" style="font-size: 2.2rem; color: var(--primary-red);">SULBAR</span>
                        </div>
                        <div class="ad-sidebar-title">SPACE IKLAN</div>
                        <div class="ad-sidebar-size">300 × 600</div>
                        <div class="ad-sidebar-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="ad-sidebar-desc">
                            Jangkau ribuan pembaca setia setiap hari di seluruh Sulawesi Barat
                        </div>
                        <a href="{{ route('page.kontak') }}" class="ad-banner-btn" style="width: 100%; justify-content: center;">
                            Pasang Iklan Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @elseif($ad->position === 'home_bottom')
                <div class="ad-banner-970">
                    <div class="ad-banner-970-inner">
                        <div class="ad-banner-970-content">
                            <div class="brand-logo-text">
                                <span class="halo" style="font-size: 1.6rem; color: #FFFFFF;">HALO</span>
                                <span class="sulbar" style="font-size: 2.4rem; color: var(--primary-red);">SULBAR</span>
                            </div>
                            <div class="ad-info-text">
                                <h3 style="font-size: 1.6rem; font-weight: 800; letter-spacing: 0.5px;">BANNER IKLAN 970 × 250</h3>
                                <div class="ad-banner-features">
                                    <span><i class="fas fa-check-circle"></i> Tingkatkan brand awareness produk & instansi</span>
                                    <span><i class="fas fa-check-circle"></i> Jangkau pembaca potensial se-Sulbar & nasional</span>
                                    <span><i class="fas fa-check-circle"></i> Solusi promosi terpadu, efektif, dan terpercaya</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('page.kontak') }}" class="ad-banner-btn" style="padding: 0.8rem 1.6rem; font-size: 0.95rem;">
                            Pasang Iklan Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @elseif($ad->position === 'in_article')
                <div class="ad-banner-728 my-4">
                    <div class="ad-banner-inner" style="padding: 0.75rem 1.25rem;">
                        <div class="ad-banner-content">
                            <div class="ad-info-text">
                                <span style="font-size: 0.6875rem; text-transform: uppercase; color: #94A3B8; font-weight: 700;">Iklan Advertorial</span>
                                <h5 style="font-size: 0.95rem; font-weight: 700; margin: 0.1rem 0;">Pasang Iklan Advertorial Anda di Sini</h5>
                            </div>
                        </div>
                        <a href="{{ route('page.kontak') }}" class="ad-banner-btn" style="padding: 0.4rem 0.9rem; font-size: 0.75rem;">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endif
