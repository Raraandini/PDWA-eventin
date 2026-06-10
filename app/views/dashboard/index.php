<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<?php
$totalJoined = count($pendaftarans);
$totalPresent = count(array_filter($pendaftarans, fn($p) => $p['status_checkin'] === 'hadir'));
$totalPending = max(0, $totalJoined - $totalPresent);
$nextEvent = null;
foreach ($pendaftarans as $p) {
    if (strtotime($p['tanggal_event'] . ' ' . $p['waktu_event']) >= time()) {
        $nextEvent = $p;
        break;
    }
}
?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>
<?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="warning"></div><?php endif; ?>

<section class="eventin-container py-8">
    <div class="grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
        <div class="relative overflow-hidden rounded-[2rem] bg-slate-950 p-7 text-white shadow-lift sm:p-9">
            <div class="shape-grid absolute inset-0 opacity-20"></div>
            <div class="relative">
                <span class="badge-soft !border-white/10 !bg-white/10 !text-lime-200">Dashboard Peserta</span>
                <h1 class="mt-5 font-display text-4xl font-black sm:text-5xl">Halo, <?= htmlspecialchars($userName) ?>.</h1>
                <p class="mt-3 max-w-xl text-slate-300">Pantau tiket, status attendance, dan sertifikat dari semua event yang kamu ikuti.</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="<?= $baseUrl ?>/#events" class="btn-accent"><i class="fa-solid fa-calendar-plus"></i> Cari Event</a>
                    <a href="<?= $baseUrl ?>/profile" class="btn-secondary !border-white/10 !bg-white/10 !text-white hover:!bg-white/15"><i class="fa-solid fa-user-gear"></i> Profil</a>
                </div>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
            <div class="bento-card p-6"><p class="text-xs font-black uppercase text-slate-400 dark:text-slate-500">Event Diikuti</p><p class="mt-2 text-4xl font-black text-slate-950 dark:text-white"><?= $totalJoined ?></p></div>
            <div class="bento-card p-6"><p class="text-xs font-black uppercase text-slate-400 dark:text-slate-500">Sudah Hadir</p><p class="mt-2 text-4xl font-black text-emerald-600 dark:text-emerald-400"><?= $totalPresent ?></p></div>
            <div class="bento-card p-6"><p class="text-xs font-black uppercase text-slate-400 dark:text-slate-500">Menunggu Scan</p><p class="mt-2 text-4xl font-black text-orange-400 dark:text-orange-400"><?= $totalPending ?></p></div>
        </div>
    </div>
</section>

<section class="eventin-container py-6">
    <div class="grid gap-6 lg:grid-cols-[.75fr_1.25fr]">
        <aside class="space-y-6">
            <div class="bento-card p-6">
                <span class="badge-soft">Upcoming</span>
                <?php if ($nextEvent): ?>
                    <h2 class="mt-4 font-display text-2xl font-black text-slate-950 dark:text-white"><?= htmlspecialchars($nextEvent['nama_event']) ?></h2>
                    <p class="mt-2 text-sm font-bold text-slate-500 dark:text-slate-400"><?= date('d F Y', strtotime($nextEvent['tanggal_event'])) ?> / <?= date('H:i', strtotime($nextEvent['waktu_event'])) ?> WIB</p>
                    <div class="mt-5 rounded-3xl bg-slate-50 dark:bg-slate-800 p-4 text-sm font-bold text-slate-600 dark:text-slate-300"><i class="fa-solid fa-location-dot mr-2 text-emerald-500"></i><?= htmlspecialchars($nextEvent['lokasi']) ?></div>
                    <a href="<?= $baseUrl ?>/ticket/<?= htmlspecialchars($nextEvent['kode_tiket']) ?>" class="btn-primary mt-5 w-full">Buka Tiket</a>
                <?php else: ?>
                    <h2 class="mt-4 font-display text-2xl font-black text-slate-950 dark:text-white">Belum ada upcoming event</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Temukan event baru yang sedang dibuka.</p>
                    <a href="<?= $baseUrl ?>/#events" class="btn-secondary mt-5 w-full">Jelajahi Event</a>
                <?php endif; ?>
            </div>

            <div class="bento-card p-6">
                <span class="badge-soft">Quick Actions</span>
                <div class="mt-5 grid gap-3">
                    <a href="<?= $baseUrl ?>/#events" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:border-emerald-200 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 dark:hover:border-emerald-800"><i class="fa-solid fa-magnifying-glass mr-2 text-emerald-500"></i>Cari event</a>
                    <a href="<?= $baseUrl ?>/profile" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:border-indigo-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 dark:hover:border-indigo-800"><i class="fa-solid fa-id-card mr-2 text-indigo-500"></i>Edit profil</a>
                    <button type="button" onclick="window.EventinToast && window.EventinToast('Riwayat seminar siap diekspor dari data peserta.', 'info')" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 text-left font-black text-slate-800 dark:text-slate-200 hover:border-purple-200 hover:bg-purple-50 dark:hover:bg-purple-900/30 dark:hover:border-purple-800"><i class="fa-solid fa-file-export mr-2 text-purple-500"></i>Export riwayat</button>
                </div>
            </div>
        </aside>

        <div class="space-y-6">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <span class="badge-soft">Tiket Saya</span>
                    <h2 class="mt-3 font-display text-3xl font-black text-slate-950 dark:text-white">Riwayat Pendaftaran</h2>
                </div>
            </div>

            <?php if (empty($pendaftarans)): ?>
                <div class="premium-card rounded-[2rem] p-10 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-lime-100 dark:bg-lime-900/40 text-lime-800 dark:text-lime-400"><i class="fa-solid fa-ticket"></i></div>
                    <h3 class="mt-5 font-display text-2xl font-black text-slate-950 dark:text-white">Belum Ada Pendaftaran</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-600 dark:text-slate-400">Tiket digital dan sertifikat akan muncul setelah kamu mendaftar event.</p>
                    <a href="<?= $baseUrl ?>/#events" class="btn-accent mt-6">Cari Event</a>
                </div>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($pendaftarans as $pendaftaran): ?>
                        <article class="bento-card p-5">
                            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if ($pendaftaran['status_checkin'] === 'hadir'): ?>
                                            <span class="badge-soft !border-emerald-200 !bg-emerald-50 !text-emerald-700"><i class="fa-solid fa-circle-check"></i> Hadir</span>
                                        <?php else: ?>
                                            <span class="badge-soft !border-orange-200 !bg-orange-50 !text-orange-600"><i class="fa-regular fa-clock"></i> Pending</span>
                                        <?php endif; ?>
                                        <span class="badge-soft"><?= htmlspecialchars($pendaftaran['kode_tiket']) ?></span>
                                    </div>
                                    <h3 class="mt-3 font-display text-2xl font-black text-slate-950 dark:text-white"><?= htmlspecialchars($pendaftaran['nama_event']) ?></h3>
                                    <p class="mt-2 text-sm font-bold text-slate-500 dark:text-slate-400"><?= date('d M Y', strtotime($pendaftaran['tanggal_event'])) ?> / <?= date('H:i', strtotime($pendaftaran['waktu_event'])) ?> WIB - <?= htmlspecialchars($pendaftaran['lokasi']) ?></p>
                                </div>
                                <div class="flex flex-col gap-2 sm:flex-row md:flex-col lg:flex-row">
                                    <a href="<?= $baseUrl ?>/ticket/<?= htmlspecialchars($pendaftaran['kode_tiket']) ?>" class="btn-primary"><i class="fa-solid fa-ticket"></i> Tiket</a>
                                    <?php if ($pendaftaran['status_checkin'] === 'hadir'): ?>
                                        <a href="<?= $baseUrl ?>/sertifikat/<?= htmlspecialchars($pendaftaran['kode_tiket']) ?>" class="btn-accent"><i class="fa-solid fa-award"></i> Sertifikat</a>
                                    <?php else: ?>
                                        <form action="<?= $baseUrl ?>/ticket/<?= htmlspecialchars($pendaftaran['kode_tiket']) ?>/batal" method="POST" id="form-batal-<?= htmlspecialchars($pendaftaran['kode_tiket']) ?>">
                                            <?= \App\Helpers\AuthHelper::getCsrfInput() ?>
                                            <button type="button" onclick="confirmCancel('<?= htmlspecialchars($pendaftaran['kode_tiket']) ?>')" class="btn-secondary !border-rose-200 !text-rose-600 hover:!bg-rose-50"><i class="fa-solid fa-xmark mr-2"></i>Batal Daftar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>

<script>
function confirmCancel(kodeTiket) {
    Swal.fire({
        title: 'Batalkan Pendaftaran?',
        text: "Tiket akan dihapus secara permanen dan kuota akan dilepas untuk peserta lain.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        customClass: {
            popup: 'rounded-[2rem] p-6',
            title: 'font-display font-black text-slate-900 text-2xl',
            htmlContainer: 'text-slate-500 font-medium',
            confirmButton: 'rounded-full px-6 py-3 font-black shadow-lg shadow-rose-500/30',
            cancelButton: 'rounded-full px-6 py-3 font-black text-slate-700'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-batal-' + kodeTiket).submit();
        }
    });
}
</script>
