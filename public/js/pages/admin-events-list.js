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

    // SweetAlert2 Confirmation for Deletion
    document.querySelectorAll('.delete-event-form').forEach(function(form) {
        const btn = form.querySelector('.btn-delete-trigger');
        if (btn) {
            btn.addEventListener('click', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus Event?',
                        text: "Data pendaftaran terkait akan ikut terhapus secara permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-lift border border-slate-100',
                            title: 'font-display font-black text-slate-900',
                            confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                            cancelButton: 'rounded-xl font-bold px-6 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Hapus event ini? Data pendaftaran terkait ikut terhapus.')) {
                        form.submit();
                    }
                }
            });
        }
    });
});
