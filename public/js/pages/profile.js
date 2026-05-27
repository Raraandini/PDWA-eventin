document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('avatar-input')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('avatar-preview');
        if (!file || !preview) return;
        preview.innerHTML = '<img src="' + URL.createObjectURL(file) + '" class="h-full w-full rounded-[2rem] object-cover" alt="Avatar preview">';
    });
});
