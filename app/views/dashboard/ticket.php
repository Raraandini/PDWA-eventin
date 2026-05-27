<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>


<section class="eventin-container py-8">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between no-print">
        <a href="<?= $baseUrl ?>/dashboard" class="inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
        <div class="flex flex-wrap gap-2">
            <button onclick="window.print()" class="btn-secondary"><i class="fa-solid fa-print"></i> Print</button>
            <button id="download-ticket" class="btn-secondary"><i class="fa-solid fa-download"></i> Download</button>
            <button id="share-ticket" class="btn-accent"><i class="fa-solid fa-share-nodes"></i> Share</button>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[480px_1fr] lg:items-center">
        <div class="relative mx-auto w-full max-w-[440px]">
            <div class="absolute inset-4 rounded-[2.5rem] bg-gradient-to-br from-lime-300/45 to-emerald-500/30 blur-2xl"></div>
            <article id="ticket-card" class="ticket-print ticket-cut relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-lift">
                <div class="bg-slate-950 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-black uppercase text-lime-300">EVENTIN PASS</p>
                            <h1 class="mt-1 font-display text-2xl font-black">Digital Ticket</h1>
                        </div>
                        <?php if ($ticket['status_checkin'] === 'hadir'): ?>
                            <span class="rounded-full bg-emerald-400 px-3 py-1 text-xs font-black text-emerald-950">HADIR</span>
                        <?php else: ?>
                            <span class="rounded-full bg-orange-300 px-3 py-1 text-xs font-black text-orange-950">BELUM SCAN</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-7">
                    <p class="text-xs font-black uppercase text-slate-400">Nama Event</p>
                    <h2 class="mt-2 font-display text-3xl font-black leading-tight text-slate-950"><?= htmlspecialchars($ticket['nama_event']) ?></h2>

                    <div class="my-7 flex flex-col items-center rounded-[2rem] border border-slate-200 bg-slate-50 p-6">
                        <div class="rounded-[1.5rem] bg-white p-4 shadow-soft">
                            <img src="<?= $baseUrl ?>/qrcode/<?= htmlspecialchars($ticket['kode_tiket']) ?>.svg" alt="QR Code Ticket" class="h-52 w-52 object-contain">
                        </div>
                        <p class="mt-4 font-mono text-lg font-black tracking-wide text-slate-950"><?= htmlspecialchars($ticket['kode_tiket']) ?></p>
                        <p class="mt-1 text-center text-xs font-bold uppercase text-slate-400">Scan di pintu masuk konferensi</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-dashed border-slate-300 pt-6">
                        <div><p class="text-[10px] font-black uppercase text-slate-400">Peserta</p><p class="mt-1 font-black text-slate-950"><?= htmlspecialchars($ticket['nama_peserta']) ?></p></div>
                        <div><p class="text-[10px] font-black uppercase text-slate-400">Instansi</p><p class="mt-1 truncate font-bold text-slate-600"><?= htmlspecialchars($ticket['instansi_peserta'] ?: '-') ?></p></div>
                        <div><p class="text-[10px] font-black uppercase text-slate-400">Tanggal</p><p class="mt-1 font-bold text-slate-700"><?= date('d F Y', strtotime($ticket['tanggal_event'])) ?></p></div>
                        <div><p class="text-[10px] font-black uppercase text-slate-400">Waktu</p><p class="mt-1 font-bold text-slate-700"><?= date('H:i', strtotime($ticket['waktu_event'])) ?> WIB</p></div>
                    </div>
                    <div class="mt-5 rounded-3xl bg-slate-950 p-4 text-sm font-bold text-white">
                        <i class="fa-solid fa-location-dot mr-2 text-lime-300"></i><?= htmlspecialchars($ticket['lokasi']) ?>
                    </div>
                </div>
            </article>
        </div>

        <div class="space-y-5">
            <span class="badge-soft">Apple Wallet Inspired</span>
            <h2 class="font-display text-4xl font-black text-slate-950 sm:text-5xl">Tiket QR premium untuk check-in cepat.</h2>
            <p class="max-w-xl text-lg leading-8 text-slate-600">Simpan tiket ini dan tunjukkan QR Code ke admin scanner saat hadir. Status attendance akan berubah realtime setelah QR valid.</p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="bento-card p-5"><i class="fa-solid fa-shield-halved text-emerald-500"></i><p class="mt-3 font-black text-slate-950">Token QR unik</p><p class="mt-1 text-sm text-slate-600">Tiap tiket hanya valid untuk satu peserta.</p></div>
                <div class="bento-card p-5"><i class="fa-solid fa-bell text-orange-400"></i><p class="mt-3 font-black text-slate-950">Status attendance</p><p class="mt-1 text-sm text-slate-600">Pending, hadir, dan sertifikat tersinkron.</p></div>
            </div>
            <?php if ($ticket['status_checkin'] === 'hadir'): ?>
                <a href="<?= $baseUrl ?>/sertifikat/<?= htmlspecialchars($ticket['kode_tiket']) ?>" class="btn-accent"><i class="fa-solid fa-award"></i> Buka Sertifikat</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
window.EventinPageData = {
    ticketCode: <?= json_encode($ticket['kode_tiket']) ?>
};
</script>
<script src="<?= $baseUrl ?>/js/pages/ticket.js"></script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
