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

});
