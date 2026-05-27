document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('export-toggle');
    const menu = document.getElementById('export-menu');
    toggle?.addEventListener('click', function() { menu?.classList.toggle('hidden'); });
    document.getElementById('attendance-filter')?.addEventListener('change', function(e) {
        const value = e.target.value;
        document.querySelectorAll('.participant-row').forEach(function(row) {
            row.classList.toggle('hidden', value !== 'all' && row.dataset.status !== value);
        });
    });
});