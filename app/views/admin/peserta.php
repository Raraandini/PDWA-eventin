<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<section class="eventin-container py-8">
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="mt-3 font-display text-4xl font-black text-slate-950 dark:text-white">Tabel Peserta</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-400">Cari peserta, filter event, pantau attendance, dan export laporan.</p>
        </div>
        <div class="relative">
            <button id="export-toggle" class="btn-accent"><i class="fa-solid fa-file-export"></i> Export Data</button>
            <div id="export-menu" class="absolute right-0 z-20 mt-2 hidden w-64 rounded-3xl border border-slate-200 bg-white p-2 shadow-lift">
                <a href="<?= $baseUrl ?>/admin/export/peserta<?= ($eventId !== null && $eventId !== '') ? '?event_id=' . urlencode($eventId) : '' ?>" class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-700 hover:bg-emerald-50"><i class="fa-solid fa-users mr-2 text-emerald-500"></i>Export Peserta</a>
                <a href="<?= $baseUrl ?>/admin/export/attendance<?= ($eventId !== null && $eventId !== '') ? '?event_id=' . urlencode($eventId) : '' ?>" class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-700 hover:bg-indigo-50"><i class="fa-solid fa-clipboard-check mr-2 text-indigo-500"></i>Export Attendance</a>
                <button onclick="window.EventinToast && window.EventinToast('Export laporan event siap dihubungkan ke format khusus.', 'success')" class="block w-full rounded-2xl px-4 py-3 text-left text-sm font-black text-slate-700 hover:bg-orange-50"><i class="fa-solid fa-chart-pie mr-2 text-orange-400"></i>Export Laporan Event</button>
            </div>
        </div>
    </div>

    <form action="<?= $baseUrl ?>/admin/peserta" method="GET" class="premium-card mb-6 rounded-[2rem] p-5">
        <div class="grid gap-3 lg:grid-cols-[1fr_320px_220px_auto]">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input class="input-modern pl-11" type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, email, instansi, kode tiket...">
                <button type="submit" class="hidden"></button>
            </div>
            <select class="input-modern" name="event_id" onchange="this.form.submit()">
                <option value="">Semua Event</option>
                <?php foreach ($events as $ev): ?>
                    <option value="<?= $ev['id'] ?>" <?= ($eventId == $ev['id']) ? 'selected' : '' ?>><?= htmlspecialchars($ev['judul']) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="attendance-filter" class="input-modern">
                <option value="all">Semua Attendance</option>
                <option value="hadir">Hadir</option>
                <option value="pending">Pending</option>
            </select>
            <a href="<?= $baseUrl ?>/admin/peserta" class="btn-secondary flex items-center justify-center px-6" title="Reset Filter"><i class="fa-solid fa-rotate-right"></i></a>
        </div>
    </form>

    <div id="participants-container" class="transition-opacity duration-300">
        <?php if (empty($registrations)): ?>
            <div class="premium-card rounded-[2rem] p-10 text-center dark:bg-slate-800 dark:border-slate-700">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 dark:bg-slate-700 text-slate-400"><i class="fa-solid fa-users-slash"></i></div>
                <h3 class="mt-5 font-display text-2xl font-black text-slate-950 dark:text-white">Tidak Ada Peserta</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Belum ada pendaftaran yang cocok dengan filter saat ini.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Instansi</th>
                            <th>Event</th>
                            <th>Status Hadir</th>
                            <th>Kode Tiket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $reg): ?>
                            <tr class="participant-row" data-status="<?= htmlspecialchars($reg['status_checkin']) ?>">
                                <td><p class="font-black text-slate-950 dark:text-white"><?= htmlspecialchars($reg['nama_peserta']) ?></p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($reg['no_hp_peserta'] ?: '-') ?></p></td>
                                <td><?= htmlspecialchars($reg['email_peserta']) ?></td>
                                <td><?= htmlspecialchars($reg['instansi_peserta'] ?: '-') ?></td>
                                <td><p class="font-bold text-slate-700 dark:text-slate-300"><?= htmlspecialchars($reg['nama_event']) ?></p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400"><?= date('d M Y H:i', strtotime($reg['tanggal_daftar'])) ?></p></td>
                                <td>
                                    <?php if ($reg['status_checkin'] === 'hadir'): ?>
                                        <span class="rounded-full border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 text-xs font-black text-emerald-700 dark:text-emerald-400">Hadir</span>
                                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400"><?= date('d M Y H:i', strtotime($reg['waktu_checkin'])) ?></p>
                                    <?php else: ?>
                                        <span class="rounded-full border border-orange-200 dark:border-orange-900 bg-orange-50 dark:bg-orange-900/30 px-3 py-1 text-xs font-black text-orange-600 dark:text-orange-400">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="font-mono font-black text-indigo-600 dark:text-indigo-400"><?= htmlspecialchars($reg['kode_tiket']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex flex-col gap-4 rounded-[2rem] border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 p-4 shadow-soft sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Menampilkan <?= count($registrations) ?> dari <?= $totalItems ?> data.</p>
                <div class="flex items-center gap-2">
                    <?php $query = ($search !== '' ? '&q=' . urlencode($search) : '') . (($eventId !== null && $eventId !== '') ? '&event_id=' . urlencode($eventId) : ''); ?>
                    <?php if ($page > 1): ?>
                        <a class="btn-secondary !py-2" href="<?= $baseUrl ?>/admin/peserta?page=<?= $page - 1 ?><?= $query ?>">Prev</a>
                    <?php endif; ?>
                    <span class="rounded-full bg-slate-950 px-4 py-2 text-sm font-black text-white"><?= $page ?> / <?= $totalPages ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a class="btn-secondary !py-2" href="<?= $baseUrl ?>/admin/peserta?page=<?= $page + 1 ?><?= $query ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/admin-peserta.js?v=<?= time() ?>"></script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
