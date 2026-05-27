document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const strength = document.getElementById('password-strength');
    function showError(input, invalid, message) {
        input.classList.toggle('!border-red-300', invalid);
        input.classList.toggle('!ring-4', invalid);
        input.classList.toggle('!ring-red-100', invalid);
        const msg = document.querySelector('[data-error-for="' + input.id + '"]');
        if (msg) {
            if (message) msg.textContent = message;
            msg.classList.toggle('hidden', !invalid);
        }
    }
    function validateInput(input) {
        const type = input.dataset.validate;
        let invalid = false;
        if (type === 'email') invalid = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
        if (type === 'phone') invalid = input.value.replace(/\D/g, '').length < 8;
        if (type === 'min') invalid = input.value.trim().length < Number(input.dataset.min || 2);
        if (type === 'password') invalid = input.value.length < 6;
        if (type === 'confirm') invalid = input.value !== password.value;
        showError(input, input.value.length > 0 && invalid);
        return !invalid;
    }
    document.querySelectorAll('[data-validate]').forEach(function(input) {
        input.addEventListener('input', function() {
            validateInput(input);
            if (confirm.value) validateInput(confirm);
            if (input === password && strength) {
                const score = Math.min(100, password.value.length * 12 + (/[A-Z]/.test(password.value) ? 20 : 0) + (/\d/.test(password.value) ? 18 : 0));
                strength.style.width = score + '%';
                strength.className = 'h-full rounded-full transition-all ' + (score > 70 ? 'bg-emerald-500' : score > 40 ? 'bg-orange-400' : 'bg-red-500');
            }
        });
    });
    if (form) {
        form.addEventListener('submit', function(e) {
            const ok = Array.from(document.querySelectorAll('[data-validate]')).every(validateInput);
            if (!ok) {
                e.preventDefault();
                window.EventinToast && window.EventinToast('Periksa kembali data registrasi.', 'warning');
            }
        });
    }
});
