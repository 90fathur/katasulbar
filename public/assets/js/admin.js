/**
 * HALO SULBAR - Admin Panel Helper Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Sidebar Toggle & Backdrop
    const toggleBtn = document.querySelector('.admin-menu-toggle');
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminSidebarBackdrop');
    const closeBtn = document.getElementById('adminSidebarClose');

    const openSidebar = () => {
        sidebar?.classList.add('show');
        backdrop?.classList.add('show');
    };

    const closeSidebar = () => {
        sidebar?.classList.remove('show');
        backdrop?.classList.remove('show');
    };

    toggleBtn?.addEventListener('click', () => {
        if (sidebar?.classList.contains('show')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    closeBtn?.addEventListener('click', closeSidebar);
    backdrop?.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });

    // 2. Slug Auto-generation
    const titleInput = document.getElementById('articleTitle');
    const slugInput = document.getElementById('articleSlug');
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', () => {
            if (!slugInput.dataset.manual) {
                slugInput.value = generateSlug(titleInput.value);
            }
        });
        slugInput.addEventListener('input', () => {
            slugInput.dataset.manual = 'true';
        });
    }

    // 3. Image Preview on File Input
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
    fileInputs.forEach(input => {
        const previewId = input.getAttribute('data-preview');
        const previewImg = document.getElementById(previewId);
        if (previewImg) {
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // 4. Quick Toggles via AJAX
    const quickToggles = document.querySelectorAll('.quick-toggle-switch input');
    quickToggles.forEach(sw => {
        sw.addEventListener('change', () => {
            const articleId = sw.getAttribute('data-article-id');
            const field = sw.getAttribute('data-field');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/articles/${articleId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ field })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    sw.checked = !sw.checked;
                    alert('Gagal memperbarui status.');
                }
            })
            .catch(() => {
                sw.checked = !sw.checked;
                alert('Terjadi kesalahan koneksi.');
            });
        });
    });
});

function generateSlug(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}
