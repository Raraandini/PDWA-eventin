document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('export-toggle');
    const menu = document.getElementById('export-menu');
    toggle?.addEventListener('click', function() { menu?.classList.toggle('hidden'); });
    
    const attendanceFilter = document.getElementById('attendance-filter');
    const applyAttendanceFilter = function() {
        const value = attendanceFilter ? attendanceFilter.value : 'all';
        document.querySelectorAll('.participant-row').forEach(function(row) {
            row.classList.toggle('hidden', value !== 'all' && row.dataset.status !== value);
        });
    };
    attendanceFilter?.addEventListener('change', applyAttendanceFilter);

    // Smooth AJAX Filter
    const form = document.querySelector('form');
    const searchInput = document.querySelector('input[name="q"]');
    const eventSelect = document.querySelector('select[name="event_id"]');
    const container = document.getElementById('participants-container');

    if (eventSelect) {
        eventSelect.removeAttribute('onchange');
    }

    async function fetchResults() {
        if (!form || !container) return;
        const url = new URL(form.action);
        if (searchInput) url.searchParams.set('q', searchInput.value);
        if (eventSelect) url.searchParams.set('event_id', eventSelect.value);

        container.style.opacity = '0.5';

        try {
            const res = await fetch(url);
            const html = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newContainer = doc.getElementById('participants-container');
            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;
                window.history.replaceState({}, '', url);
                applyAttendanceFilter();
            }
        } catch (err) {
            console.error('Failed to fetch results', err);
        } finally {
            container.style.opacity = '1';
        }
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchResults();
        });
    }

    if (eventSelect) {
        eventSelect.addEventListener('change', fetchResults);
    }

    if (searchInput) {
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(fetchResults, 500);
        });
    }
});
