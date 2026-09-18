/**
 * Cipamilk E-Commerce — Custom JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // Navbar scroll effect
    // ============================================
    const navbar = document.querySelector('.navbar-cipamilk');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
                navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.15)';
            } else {
                navbar.classList.remove('navbar-scrolled');
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            }
        });
    }

    // ============================================
    // Smooth scroll for anchor links
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ============================================
    // Scroll reveal animation
    // ============================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });

    // Add style for revealed elements
    const style = document.createElement('style');
    style.textContent = `.revealed { opacity: 1 !important; transform: translateY(0) !important; }`;
    document.head.appendChild(style);

    // ============================================
    // Quantity input controls
    // ============================================
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            let val = parseInt(input.value) || 1;
            if (val > 1) {
                input.value = val - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            const max = parseInt(input.getAttribute('max')) || 999;
            let val = parseInt(input.value) || 1;
            if (val < max) {
                input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });


    // ============================================
    // Category filter
    // ============================================
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ============================================
    // Form validation visual feedback
    // ============================================
    document.querySelectorAll('.form-control-cipamilk').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.required && !this.value.trim()) {
                this.style.borderColor = '#E74C3C';
            } else if (this.value.trim()) {
                this.style.borderColor = '#4F772D';
            }
        });

        input.addEventListener('focus', function() {
            this.style.borderColor = '#4DA8DA';
        });
    });

    // ============================================
    // Product image placeholder with icon
    // ============================================
    document.querySelectorAll('.product-card-image img').forEach(img => {
        img.addEventListener('error', function() {
            this.parentElement.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#B2BEC3;font-size:3rem;"><i class="fas fa-image"></i></div>';
        });
    });

    document.querySelectorAll('.detail-image-wrapper img').forEach(img => {
        img.addEventListener('error', function() {
            this.parentElement.innerHTML = '<div style="width:100%;height:400px;display:flex;align-items:center;justify-content:center;color:#B2BEC3;font-size:5rem;background:#f8f9fa;"><i class="fas fa-image"></i></div>';
        });
    });

    // ============================================
    // Clickable Product Cards
    // ============================================
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.product-card[data-href]');
        if (card && !e.target.closest('a, button')) {
            const href = card.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        }
    });

    // ============================================
    // Floating Pop-up Notifications
    // ============================================
    function setupPopupDismiss(popup) {
        if (!popup) return;
        setTimeout(() => {
            if (popup && popup.parentElement) {
                popup.classList.add('hiding');
                setTimeout(() => popup.remove(), 350);
            }
        }, 4500);
    }

    // Auto-dismiss any popups rendered on page load
    document.querySelectorAll('.cipamilk-popup').forEach(popup => {
        setupPopupDismiss(popup);
    });

    window.closeCipamilkPopup = function(btn) {
        const popup = btn.closest('.cipamilk-popup');
        if (popup) {
            popup.classList.add('hiding');
            setTimeout(() => popup.remove(), 350);
        }
    };

    window.showCipamilkPopup = function(message, type = 'success', actionBtn = null) {
        let container = document.getElementById('cipamilkPopupContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'cipamilkPopupContainer';
            container.className = 'cipamilk-popup-container';
            document.body.appendChild(container);
        }

        const popup = document.createElement('div');
        popup.className = `cipamilk-popup popup-${type === 'error' ? 'danger' : 'success'}`;
        popup.setAttribute('role', 'alert');

        const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
        const title = type === 'error' ? 'Pemberitahuan' : 'Berhasil!';

        let btnHtml = '';
        if (actionBtn && actionBtn.url) {
            btnHtml = `<a href="${actionBtn.url}" class="popup-action-btn"><i class="fas fa-shopping-cart mr-1"></i> ${actionBtn.text || 'Lihat'}</a>`;
        }

        popup.innerHTML = `
            <div class="popup-icon"><i class="fas ${icon}"></i></div>
            <div class="popup-content">
                <div class="popup-title">${title}</div>
                <div class="popup-msg">${message}</div>
                ${btnHtml}
            </div>
            <button type="button" class="popup-close-btn" onclick="closeCipamilkPopup(this)">&times;</button>
        `;

        container.appendChild(popup);
        setupPopupDismiss(popup);
    };

    // Alias for compatibility
    window.showCipamilkToast = function(message, type = 'success', cartUrl = null) {
        const btn = cartUrl ? { url: cartUrl, text: 'Lihat Keranjang' } : null;
        window.showCipamilkPopup(message, type, btn);
    };

    // ============================================
    // AJAX Add to Cart (Stay on Product Page)
    // ============================================
    const addToCartForm = document.getElementById('form-add-to-cart');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menambahkan...';
            }

            const formData = new FormData(this);
            formData.append('ajax', '1');

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update cart badge in navbar
                    const badge = document.getElementById('nav-cart-badge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                        badge.style.display = 'flex';
                        badge.classList.remove('bump');
                        // Force DOM reflow to re-trigger CSS animation
                        void badge.offsetWidth;
                        badge.classList.add('bump');
                    }

                    // Show pop-up notification without affecting layout
                    const cartLink = document.getElementById('nav-cart-btn') ? document.getElementById('nav-cart-btn').href : null;
                    showCipamilkPopup(data.message || 'Produk berhasil ditambahkan ke keranjang!', 'success', cartLink ? { url: cartLink, text: 'Lihat Keranjang' } : null);

                    // Button feedback
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Berhasil Ditambahkan!';
                        submitBtn.classList.remove('btn-primary-cipamilk');
                        submitBtn.classList.add('btn-success');
                        setTimeout(() => {
                            submitBtn.innerHTML = originalBtnHtml;
                            submitBtn.classList.remove('btn-success');
                            submitBtn.classList.add('btn-primary-cipamilk');
                            submitBtn.disabled = false;
                        }, 1800);
                    }
                } else {
                    if (data.redirect) {
                        showCipamilkPopup(data.message || 'Silakan login terlebih dahulu.', 'error');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1200);
                    } else {
                        showCipamilkPopup(data.message || 'Gagal menambahkan produk.', 'error');
                        if (submitBtn) {
                            submitBtn.innerHTML = originalBtnHtml;
                            submitBtn.disabled = false;
                        }
                    }
                }
            })
            .catch(err => {
                console.warn('AJAX cart add failed, falling back to standard submit:', err);
                addToCartForm.submit();
            });
        });
    }

});
