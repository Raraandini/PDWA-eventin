<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>
<?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

<section class="eventin-container py-8">
    <div class="grid gap-6 lg:grid-cols-[1.25fr_.75fr]">
        <div class="relative overflow-hidden rounded-[2rem] bg-slate-950 p-8 text-white shadow-lift">
            <div class="shape-grid absolute inset-0 opacity-20"></div>
            <div class="relative">
                <span class="badge-soft !border-white/10 !bg-white/10 !text-lime-200">Dashboard Admin</span>
                <h1 class="mt-5 font-display text-5xl font-black leading-none">Selamat datang, <?= htmlspecialchars($userName) ?>.</h1>
                <p class="mt-4 max-w-2xl leading-7 text-slate-300">Pantau seminar aktif, jumlah peserta, status kehadiran, dan proses check-in tiket QR.</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="<?= $baseUrl ?>/admin/scan" class="btn-accent"><i class="fa-solid fa-qrcode"></i> Mulai Scan QR</a>
                    <a href="<?= $baseUrl ?>/admin/event/create" class="btn-secondary !border-white/10 !bg-white/10 !text-white hover:!bg-white/15"><i class="fa-solid fa-calendar-plus"></i> Buat Event</a>
                </div>
            </div>
        </div>
        <div class="bento-card p-7">
            <p class="text-xs font-black uppercase text-slate-400 dark:text-slate-500">Attendance Rate</p>
            <p class="mt-3 text-6xl font-black text-slate-950 dark:text-white"><?= $attendancePercent ?>%</p>
            <div class="mt-5 h-4 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full rounded-full bg-gradient-to-r from-lime-400 to-emerald-500 w-[<?= min(100, $attendancePercent) ?>%]"></div>
            </div>
            <p class="mt-4 text-sm font-bold text-slate-500 dark:text-slate-400"><?= $totalHadir ?> hadir dari <?= $totalPendaftaran ?> pendaftaran.</p>
        </div>
    </div>
</section>

<section class="eventin-container py-4">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <?php
        $stats = [
            ['Total Event', $totalEvents, 'fa-calendar-days', 'text-indigo-500', $activeEvents . ' aktif'],
            ['Total Peserta', $totalPeserta, 'fa-users', 'text-purple-500', 'user peserta'],
            ['Pendaftaran', $totalPendaftaran, 'fa-ticket', 'text-emerald-500', $totalPendaftaran - $totalHadir . ' pending'],
            ['Hadir', $totalHadir, 'fa-user-check', 'text-lime-600', 'check-in sukses'],
            ['Event Aktif', $activeEvents, 'fa-bolt', 'text-orange-400', 'open registration'],
        ];
        foreach ($stats as $stat):
        ?>
            <div class="bento-card p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-black uppercase text-slate-400 dark:text-slate-500"><?= $stat[0] ?></p>
                    <i class="fa-solid <?= $stat[2] ?> <?= $stat[3] ?>"></i>
                </div>
                <p class="mt-3 text-4xl font-black text-slate-950 dark:text-white"><?= $stat[1] ?></p>
                <p class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400"><?= $stat[4] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="eventin-container py-8">
    <div class="grid gap-6 lg:grid-cols-[.8fr_1.2fr]">
        <div class="space-y-6">
            <div class="bento-card p-6">
                <span class="badge-soft">Quick Action</span>
                <div class="mt-5 grid gap-3">
                    <a href="<?= $baseUrl ?>/admin/events" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:border-indigo-200 dark:hover:border-indigo-800"><i class="fa-solid fa-calendar-check mr-2 text-indigo-500"></i>Kelola Event</a>
                    <a href="<?= $baseUrl ?>/admin/peserta" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:border-emerald-200 dark:hover:border-emerald-800"><i class="fa-solid fa-users-gear mr-2 text-emerald-500"></i>Tabel Peserta</a>
                    <a href="<?= $baseUrl ?>/admin/scan" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:bg-lime-50 dark:hover:bg-lime-900/30 hover:border-lime-200 dark:hover:border-lime-800"><i class="fa-solid fa-barcode mr-2 text-lime-600"></i>Scanner Kehadiran</a>
                    <a href="<?= $baseUrl ?>/admin/petugas" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:bg-cyan-50 dark:hover:bg-cyan-900/30 hover:border-cyan-200 dark:hover:border-cyan-800"><i class="fa-solid fa-id-badge mr-2 text-cyan-500"></i>Manajemen Petugas</a>
                    <a href="<?= $baseUrl ?>/admin/export/attendance" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 font-black text-slate-800 dark:text-slate-200 hover:bg-orange-50 dark:hover:bg-orange-900/30 hover:border-orange-200 dark:hover:border-orange-800"><i class="fa-solid fa-file-export mr-2 text-orange-400"></i>Export Attendance</a>
                </div>
            </div>
            <div class="bento-card p-6">
                <span class="badge-soft">Grafik Statistik</span>
                <div class="mt-5 space-y-4">
                    <?php foreach ([['Registrasi', $totalPendaftaran, '#6366f1'], ['Hadir', $totalHadir, '#10b981'], ['Pending', max(0, $totalPendaftaran - $totalHadir), '#fb923c']] as $bar): ?>
                        <?php $w = $totalPendaftaran > 0 ? min(100, ($bar[1] / $totalPendaftaran) * 100) : 0; ?>
                        <div>
                            <div class="mb-2 flex justify-between text-xs font-black uppercase text-slate-500 dark:text-slate-400"><span><?= $bar[0] ?></span><span><?= $bar[1] ?></span></div>
                            <div class="h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"><div class="h-full rounded-full w-[<?= $w ?>%] bg-[<?= $bar[2] ?>]"></div></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="premium-card rounded-[2rem] p-6">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <span class="badge-soft">Recent Scan</span>
                    <h2 class="mt-3 font-display text-3xl font-black text-slate-950 dark:text-white">Log Aktivitas</h2>
                </div>
                <a href="<?= $baseUrl ?>/admin/scan" class="btn-secondary">Scanner</a>
            </div>
            <?php if (empty($recentLogs)): ?>
                <div class="rounded-[1.75rem] border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-10 text-center">
                    <i class="fa-solid fa-list-check text-3xl text-slate-300 dark:text-slate-600"></i>
                    <h3 class="mt-4 font-display text-xl font-black text-slate-950 dark:text-white">Belum Ada Attendance</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Aktivitas scan terbaru akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentLogs as $log): ?>
                        <?php
                        $statusClass = $log['status'] === 'berhasil' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($log['status'] === 'duplikat' ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-red-50 text-red-700 border-red-200');
                        ?>
                        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-black text-slate-950 dark:text-white"><?= htmlspecialchars($log['nama_peserta'] ?? '-') ?></p>
                                    <p class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400"><?= htmlspecialchars($log['nama_event'] ?? '-') ?> / <?= date('d M Y H:i', strtotime($log['waktu_scan'])) ?></p>
                                </div>
                                <span class="rounded-full border px-3 py-1 text-xs font-black uppercase <?= $statusClass ?>"><?= htmlspecialchars($log['status']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
