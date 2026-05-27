/**
 * ticket.js
 * Requires window.EventinPageData = { ticketCode } set inline by PHP.
 */
document.addEventListener('DOMContentLoaded', function() {
    const ticketCode = (window.EventinPageData && window.EventinPageData.ticketCode) || '';
    document.getElementById('share-ticket')?.addEventListener('click', async function() {
        const url = location.href;
        if (navigator.share) {
            await navigator.share({ title: 'Tiket Eventin ' + ticketCode, url });
        } else {
            await navigator.clipboard.writeText(url);
            window.EventinToast && window.EventinToast('Link tiket disalin.', 'success');
        }
    });
    document.getElementById('download-ticket')?.addEventListener('click', function() {
        window.print();
    });
});
