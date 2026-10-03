/**
 * HALO SULBAR - "Suara Sulbar, Untuk Semua"
 * Frontend Core Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Dark Mode Management
    initDarkMode();

    // 2. Hero Slider
    initHeroSlider();

    // 3. Tab Switching (Terpopuler / Terbaru / Komentar)
    initTabs();

    // 4. Live Search
    initLiveSearch();

    // 5. Mobile Drawer Menu
    initMobileDrawer();

    // 6. YouTube Modal Player
    initVideoModal();

    // 7. Newsletter Subscription Form
    initNewsletterForm();

    // 8. Copy Link Toast
    initCopyLink();
});

/* -------------------------------------------------------------------------- */
/* 1. Dark Mode                                                               */
/* -------------------------------------------------------------------------- */
function initDarkMode() {
    const themeBtn = document.getElementById('themeToggleBtn');
    const savedTheme = localStorage.getItem('halosulbar_theme');

    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        updateThemeIcon(true);
    }

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            if (isDark) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('halosulbar_theme', 'light');
                updateThemeIcon(false);
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('halosulbar_theme', 'dark');
                updateThemeIcon(true);
            }
        });
    }
}

function updateThemeIcon(isDark) {
    const icon = document.querySelector('#themeToggleBtn i');
    if (icon) {
        icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
    }
}

/* -------------------------------------------------------------------------- */
/* 2. Hero Slider                                                             */
/* -------------------------------------------------------------------------- */
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const prevBtn = document.querySelector('.hero-slider-nav.prev');
    const nextBtn = document.querySelector('.hero-slider-nav.next');

    if (!slides.length) return;

    let currentIdx = 0;
    let autoSlideInterval = null;

    function goToSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        currentIdx = (index + slides.length) % slides.length;

        slides[currentIdx].classList.add('active');
        if (dots[currentIdx]) dots[currentIdx].classList.add('active');
    }

    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(() => {
            goToSlide(currentIdx + 1);
        }, 5500);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) clearInterval(autoSlideInterval);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIdx + 1);
            startAutoSlide();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIdx - 1);
            startAutoSlide();
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            goToSlide(idx);
            startAutoSlide();
        });
    });

    const sliderWrap = document.querySelector('.hero-slider-wrap');
    if (sliderWrap) {
        sliderWrap.addEventListener('mouseenter', stopAutoSlide);
        sliderWrap.addEventListener('mouseleave', startAutoSlide);
    }

    startAutoSlide();
}

/* -------------------------------------------------------------------------- */
/* 3. Tab Switching                                                           */
/* -------------------------------------------------------------------------- */
function initTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    if (!tabBtns.length) return;

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-tab');
            const parent = btn.closest('.widget-box');

            parent.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            parent.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            const pane = parent.querySelector(`#${target}`);
            if (pane) pane.classList.add('active');
        });
    });
}

/* -------------------------------------------------------------------------- */
/* 4. Live Search with Debounce                                               */
/* -------------------------------------------------------------------------- */
function initLiveSearch() {
    const searchInputs = document.querySelectorAll('.nav-search-input');

    searchInputs.forEach(input => {
        let debounceTimer;
        let resultsBox = input.parentElement.querySelector('.live-search-results');

        if (!resultsBox) {
            resultsBox = document.createElement('div');
            resultsBox.className = 'live-search-results';
            input.parentElement.appendChild(resultsBox);
        }

        input.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                resultsBox.style.display = 'none';
                resultsBox.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/api/live-search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.results && data.results.length > 0) {
                            let html = '';
                            data.results.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="live-search-item">
                                        <img src="${item.thumbnail}" class="live-search-thumb" alt="${item.title}">
                                        <div class="live-search-info">
                                            <div style="font-size:0.65rem;font-weight:700;color:${item.category_color};text-transform:uppercase">${item.category}</div>
                                            <div class="live-search-title">${item.title}</div>
                                            <div style="font-size:0.7rem;color:#94a3b8">${item.date}</div>
                                        </div>
                                    </a>
                                `;
                            });
                            resultsBox.innerHTML = html;
                            resultsBox.style.display = 'block';
                        } else {
                            resultsBox.innerHTML = '<div style="padding:1rem;text-align:center;font-size:0.8rem;color:#94a3b8">Tidak ada berita ditemukan</div>';
                            resultsBox.style.display = 'block';
                        }
                    })
                    .catch(() => {
                        resultsBox.style.display = 'none';
                    });
            }, 300);
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.style.display = 'none';
            }
        });
    });
}

/* -------------------------------------------------------------------------- */
/* 5. Mobile Drawer                                                           */
/* -------------------------------------------------------------------------- */
function initMobileDrawer() {
    const toggleBtn = document.querySelector('.nav-mobile-toggle');
    const drawer = document.querySelector('.mobile-drawer');
    const backdrop = document.querySelector('.mobile-drawer-backdrop');
    const closeBtn = document.querySelector('.mobile-drawer-close');

    if (!toggleBtn || !drawer || !backdrop) return;

    function openDrawer() {
        drawer.classList.add('show');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.remove('show');
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);
}

/* -------------------------------------------------------------------------- */
/* 6. YouTube Video Modal Player                                              */
/* -------------------------------------------------------------------------- */
function initVideoModal() {
    const triggers = document.querySelectorAll('[data-video-id]');
    let modal = document.querySelector('.video-modal-backdrop');

    if (!modal) {
        modal = document.createElement('div');
        modal.className = 'video-modal-backdrop';
        modal.innerHTML = `
            <div class="video-modal-container">
                <button class="video-modal-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
                <div class="video-modal-iframe-wrap">
                    <iframe id="videoModalIframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    const iframe = modal.querySelector('#videoModalIframe');
    const container = modal.querySelector('.video-modal-container');
    const closeBtn = modal.querySelector('.video-modal-close');

    function openVideo(videoId, isShorts = false) {
        if (isShorts) {
            container.classList.add('is-shorts');
        } else {
            container.classList.remove('is-shorts');
        }

        iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0`;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeVideo() {
        modal.classList.remove('show');
        iframe.src = '';
        document.body.style.overflow = '';
    }

    triggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            // If inside a link, prevent default if we want modal popup
            const videoId = trigger.getAttribute('data-video-id');
            const isShorts = trigger.getAttribute('data-is-shorts') === 'true';

            if (videoId) {
                e.preventDefault();
                openVideo(videoId, isShorts);
            }
        });
    });

    closeBtn.addEventListener('click', closeVideo);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeVideo();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeVideo();
        }
    });
}

/* -------------------------------------------------------------------------- */
/* 7. Newsletter Subscription Form                                            */
/* -------------------------------------------------------------------------- */
function initNewsletterForm() {
    const forms = document.querySelectorAll('.newsletter-form');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = form.querySelector('.newsletter-input');
            const btn = form.querySelector('.newsletter-btn');
            const email = input.value.trim();

            if (!email) return;

            btn.disabled = true;
            btn.textContent = 'Memproses...';

            fetch('/newsletter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || 'Terima kasih telah berlangganan!');
                input.value = '';
                btn.disabled = false;
                btn.textContent = 'Berlangganan';
            })
            .catch(() => {
                alert('Terjadi kesalahan. Silakan coba beberapa saat lagi.');
                btn.disabled = false;
                btn.textContent = 'Berlangganan';
            });
        });
    });
}

/* -------------------------------------------------------------------------- */
/* 8. Copy Link Toast                                                         */
/* -------------------------------------------------------------------------- */
function initCopyLink() {
    const copyBtns = document.querySelectorAll('.share-btn.copy');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            navigator.clipboard.writeText(window.location.href).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
                setTimeout(() => {
                    btn.innerHTML = orig;
                }, 2000);
            });
        });
    });
}
