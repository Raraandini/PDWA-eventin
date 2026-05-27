document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById('event-search');
    const filter = document.getElementById('event-filter');
    const cards = Array.from(document.querySelectorAll('.event-card'));
    const empty = document.getElementById('event-empty');
    function applyFilter() {
        const q = (search?.value || '').toLowerCase();
        const status = filter?.value || 'all';
        let shown = 0;
        cards.forEach(function(card) {
            const matchText = card.dataset.title.includes(q);
            const matchStatus = status === 'all' || card.dataset.status === status;
            const visible = matchText && matchStatus;
            card.classList.toggle('hidden', !visible);
            if (visible) shown++;
        });
        if (empty) empty.classList.toggle('hidden', shown !== 0);
    }
    if (search) search.addEventListener('input', applyFilter);
    if (filter) filter.addEventListener('change', applyFilter);
});
