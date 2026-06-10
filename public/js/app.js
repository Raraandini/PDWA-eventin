document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            const icon = menuBtn.querySelector('i');
            if (icon) icon.className = mobileMenu.classList.contains('hidden') ? 'fa-solid fa-bars' : 'fa-solid fa-xmark';
        });
    }

    // Password Toggle
    document.querySelectorAll('[data-password-toggle]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const input = document.querySelector(btn.getAttribute('data-password-toggle'));
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            const icon = btn.querySelector('i');
            if (icon) icon.className = input.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
        });
    });

    // Loading Submit
    document.querySelectorAll('[data-loading-submit]').forEach(function(form) {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            btn.dataset.originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';
        });
    });

    // Countdown
    document.querySelectorAll('[data-countdown]').forEach(function(root) {
        const target = new Date(root.dataset.countdown.replace(/-/g, '/')).getTime();
        const slots = {
            days: root.querySelector('[data-days]'),
            hours: root.querySelector('[data-hours]'),
            minutes: root.querySelector('[data-minutes]'),
            seconds: root.querySelector('[data-seconds]')
        };
        function tick() {
            const distance = target - Date.now();
            if (distance <= 0) {
                Object.keys(slots).forEach(function(k) { if (slots[k]) slots[k].textContent = '00'; });
                return;
            }
            const days = Math.floor(distance / 86400000);
            const hours = Math.floor((distance % 86400000) / 3600000);
            const minutes = Math.floor((distance % 3600000) / 60000);
            const seconds = Math.floor((distance % 60000) / 1000);
            if (slots.days) slots.days.textContent = String(days).padStart(2, '0');
            if (slots.hours) slots.hours.textContent = String(hours).padStart(2, '0');
            if (slots.minutes) slots.minutes.textContent = String(minutes).padStart(2, '0');
            if (slots.seconds) slots.seconds.textContent = String(seconds).padStart(2, '0');
        }
        tick();
        setInterval(tick, 1000);
    });

    // Toast System
    window.EventinToast = function(message, type) {
        if (typeof Swal !== 'undefined') {
            const iconMap = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 4200,
                timerProgressBar: true,
                icon: iconMap[type] || 'info',
                title: message,
                customClass: {
                    popup: 'rounded-2xl shadow-lift border border-slate-100',
                    title: 'font-bold text-sm text-slate-800'
                }
            });
        } else {
            const root = document.getElementById('toast-root');
            if (!root) return;
            const palette = {
                success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                error: 'border-red-200 bg-red-50 text-red-700',
                warning: 'border-orange-200 bg-orange-50 text-orange-700',
                info: 'border-slate-200 bg-white text-slate-700'
            };
            const el = document.createElement('div');
            el.className = 'animate-fadeUp rounded-2xl border px-4 py-3 shadow-soft text-sm font-bold ' + (palette[type || 'info'] || palette.info);
            el.textContent = message;
            root.appendChild(el);
            setTimeout(function() { el.remove(); }, 4200);
        }
    };

    document.querySelectorAll('[data-toast]').forEach(function(el) {
        window.EventinToast(el.dataset.toast, el.dataset.toastType || 'info');
    });
});
