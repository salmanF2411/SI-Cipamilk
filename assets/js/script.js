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
    // Alert auto-dismiss
    // ============================================
    document.querySelectorAll('.alert-cipamilk').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
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
    // Toast Notification Helper
    // ============================================
    window.showCipamilkToast = function(message, type = 'success', cartUrl = null) {
        const existing = document.querySelector('.cipamilk-toast');
        if (existing) {
            existing.remove();
        }

        const toast = document.createElement('div');
        toast.className = `cipamilk-toast toast-${type}`;

        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        let btnHtml = '';
        if (cartUrl && type === 'success') {
            btnHtml = `<a href="${cartUrl}" class="toast-btn"><i class="fas fa-shopping-cart mr-1"></i> Lihat Keranjang</a>`;
        }

        toast.innerHTML = `
            <i class="fas ${icon}"></i>
            <span class="toast-message">${message}</span>
            ${btnHtml}
        `;

        document.body.appendChild(toast);

        // Animate entrance
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        // Auto dismiss
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
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

                    // Show notification
                    const cartLink = document.getElementById('nav-cart-btn') ? document.getElementById('nav-cart-btn').href : null;
                    showCipamilkToast(data.message || 'Produk berhasil ditambahkan ke keranjang!', 'success', cartLink);

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
                        showCipamilkToast(data.message || 'Silakan login terlebih dahulu.', 'error');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1200);
                    } else {
                        showCipamilkToast(data.message || 'Gagal menambahkan produk.', 'error');
                        if (submitBtn) {
                            submitBtn.innerHTML = originalBtnHtml;
                            submitBtn.disabled = false;
                        }
                    }
                }
            })
            .catch(err => {
                console.warn('AJAX cart add failed, falling back to standard submit:', err);
                // Fallback: submit standard form
                addToCartForm.submit();
            });
        });
    }

});
