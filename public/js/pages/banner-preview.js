document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('banner')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('banner-preview');
        if (!file || !preview) return;
        const url = URL.createObjectURL(file);
        preview.innerHTML = '<img src="' + url + '" class="h-full w-full object-cover" alt="Preview banner">';
    });
});
