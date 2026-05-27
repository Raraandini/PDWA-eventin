document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById('admin-event-search');
    const status = document.getElementById('admin-event-status');
    const rows = Array.from(document.querySelectorAll('.admin-event-row'));
    const empty = document.getElementById('admin-event-empty');
    function filterRows() {
        const q = (search?.value || '').toLowerCase();
        const s = status?.value || 'all';
        let shown = 0;
        rows.forEach(function(row) {
            const visible = row.dataset.title.includes(q) && (s === 'all' || row.dataset.status === s);
            row.classList.toggle('hidden', !visible);
            if (visible) shown++;
        });
        empty?.classList.toggle('hidden', shown > 0);
    }
    search?.addEventListener('input', filterRows);
    status?.addEventListener('change', filterRows);
});