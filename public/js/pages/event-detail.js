/**
 * event-detail.js
 * Requires window.EventinPageData = { eventTitle, eventUrl } set inline by PHP.
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-event-form');
    const modal = document.getElementById('registration-modal');
    if (form && modal) {
        form.addEventListener('submit', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    }
    const share = document.getElementById('share-event');
    if (share) {
        share.addEventListener('click', async function() {
            const pd = window.EventinPageData || {};
            const data = { title: pd.eventTitle || document.title, text: pd.eventTitle || '', url: pd.eventUrl || location.href };
            if (navigator.share) {
                await navigator.share(data);
            } else {
                await navigator.clipboard.writeText(data.url);
                window.EventinToast && window.EventinToast('Link event disalin.', 'success');
            }
        });
    }
});