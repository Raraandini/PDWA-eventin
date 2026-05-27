<?php include __DIR__ . '/layouts/header.php'; ?>

<?php
$registeredCount = max(0, (int)$event['kuota'] - (int)$remainingKuota);
$quotaPercent = $event['kuota'] > 0 ? min(100, ($registeredCount / $event['kuota']) * 100) : 0;
$eventDateTime = $event['tanggal_event'] . ' ' . $event['waktu_event'];
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '');
$flashError = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<?php if (!empty($flashError)): ?>
    <div data-toast="<?= htmlspecialchars($flashError) ?>" data-toast-type="<?= str_contains(strtolower($flashError), 'penuh') || str_contains(strtolower($flashError), 'sudah') ? 'warning' : 'error' ?>"></div>
<?php endif; ?>

<section class="eventin-container py-8">
    <a href="<?= $baseUrl ?>/" class="mb-6 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
        <div class="space-y-6">
            <div class="relative min-h-[420px] overflow-hidden rounded-[2rem] bg-slate-950 shadow-lift">
                <?php if (!empty($event['banner']) && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $event['banner'])): ?>
                    <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($event['banner']) ?>" alt="<?= htmlspecialchars($event['judul']) ?>" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
                <?php else: ?>
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-indigo-950"></div>
                    <div class="shape-grid absolute inset-0 opacity-20"></div>
                <?php endif; ?>
                <div class="relative flex min-h-[420px] flex-col justify-end p-6 sm:p-10 text-white">
                    <div class="mb-5 flex flex-wrap gap-2">
                        <span class="badge-soft !border-white/15 !bg-white/15 !text-lime-200"><?= htmlspecialchars(strtoupper($event['status'])) ?></span>
                        <span class="badge-soft !border-white/15 !bg-white/15 !text-white"><i class="fa-solid fa-location-dot text-emerald-300"></i><?= htmlspecialchars($event['lokasi']) ?></span>
                    </div>
                    <h1 class="font-display text-4xl font-black leading-tight sm:text-6xl"><?= htmlspecialchars($event['judul']) ?></h1>
                    <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-200"><?= htmlspecialchars(mb_substr(strip_tags($event['deskripsi']), 0, 190)) ?><?= mb_strlen(strip_tags($event['deskripsi'])) > 190 ? '...' : '' ?></p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bento-card p-6"><i class="fa-regular fa-calendar text-emerald-500"></i><p class="mt-3 text-xs font-black uppercase text-slate-400">Tanggal</p><p class="font-black text-slate-950"><?= date('d F Y', strtotime($event['tanggal_event'])) ?></p></div>
                <div class="bento-card p-6"><i class="fa-regular fa-clock text-indigo-500"></i><p class="mt-3 text-xs font-black uppercase text-slate-400">Waktu</p><p class="font-black text-slate-950"><?= date('H:i', strtotime($event['waktu_event'])) ?> WIB</p></div>
                <div class="bento-card p-6"><i class="fa-solid fa-user-tie text-purple-500"></i><p class="mt-3 text-xs font-black uppercase text-slate-400">Organizer</p><p class="font-black text-slate-950"><?= htmlspecialchars($event['pembuat'] ?? 'Eventin Team') ?></p></div>
            </div>

            <div id="overview" class="premium-card rounded-[2rem] p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <span class="badge-soft">Overview</span>
                        <h2 class="mt-3 font-display text-3xl font-black text-slate-950">Informasi Event</h2>
                    </div>
                    <button type="button" id="share-event" class="btn-secondary no-print"><i class="fa-solid fa-share-nodes"></i> Share</button>
                </div>
                <div class="mt-6 whitespace-pre-line text-base leading-8 text-slate-600"><?= htmlspecialchars($event['deskripsi']) ?></div>
            </div>


            <div class="premium-card rounded-[2rem] p-6 sm:p-8">
                <span class="badge-soft">Lokasi</span>
                <div class="mt-5 rounded-[1.5rem] border border-slate-200 bg-slate-100 p-6">
                    <p class="font-display text-2xl font-black text-slate-950"><?= htmlspecialchars($event['lokasi']) ?></p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Tunjukkan tiket QR digital di area registrasi untuk validasi attendance.</p>
                </div>
            </div>
        </div>

        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="premium-card rounded-[2rem] p-6">
                <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white" data-countdown="<?= htmlspecialchars($eventDateTime) ?>">
                    <p class="text-xs font-black uppercase text-slate-400">Countdown Realtime</p>
                    <div class="mt-4 grid grid-cols-4 gap-2 text-center">
                        <div><b class="text-2xl text-lime-300" data-days>00</b><span class="block text-[10px] font-bold text-slate-400">Hari</span></div>
                        <div><b class="text-2xl" data-hours>00</b><span class="block text-[10px] font-bold text-slate-400">Jam</span></div>
                        <div><b class="text-2xl" data-minutes>00</b><span class="block text-[10px] font-bold text-slate-400">Menit</span></div>
                        <div><b class="text-2xl text-emerald-300" data-seconds>00</b><span class="block text-[10px] font-bold text-slate-400">Detik</span></div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="mb-2 flex items-center justify-between text-xs font-black uppercase text-slate-500">
                        <span>Sisa Kuota</span>
                        <span><?= $remainingKuota ?> / <?= $event['kuota'] ?></span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-lime-400 to-emerald-500 w-[<?= $quotaPercent ?>%]"></div>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <?php if (\App\Helpers\AuthHelper::isLoggedIn()): ?>
                        <?php if (\App\Helpers\AuthHelper::isAdmin()): ?>
                            <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-4 text-sm font-bold text-indigo-700">Admin dapat memantau peserta melalui menu monitoring.</div>
                            <a href="<?= $baseUrl ?>/admin/peserta?event_id=<?= $event['id'] ?>" class="btn-primary w-full">Monitoring Peserta</a>
                        <?php elseif ($isRegistered): ?>
                            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700"><i class="fa-solid fa-circle-check mr-2"></i>Anda sudah terdaftar.</div>
                            <a href="<?= $baseUrl ?>/dashboard" class="btn-accent w-full">Lihat Tiket Digital</a>
                        <?php elseif ($remainingKuota <= 0): ?>
                            <button disabled class="w-full rounded-3xl bg-slate-100 px-4 py-4 font-black text-slate-400">Kuota Penuh</button>
                        <?php elseif ($event['status'] !== 'open'): ?>
                            <button disabled class="w-full rounded-3xl bg-slate-100 px-4 py-4 font-black text-slate-400">Registrasi Ditutup</button>
                        <?php else: ?>
                            <form data-loading-submit id="register-event-form" action="<?= $baseUrl ?>/event/<?= $event['id'] ?>/daftar" method="POST">
                                <button type="submit" class="btn-accent w-full"><i class="fa-solid fa-ticket"></i> Daftar Sekarang</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>/login?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn-accent w-full"><i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk untuk Daftar</a>
                    <?php endif; ?>
                    <a href="#overview" class="btn-secondary w-full">Baca Detail</a>
                </div>
            </div>
        </aside>
    </div>
</section>

<div id="registration-modal" class="fixed inset-0 z-[90] hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
    <div class="premium-card max-w-sm rounded-[2rem] p-7 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-100 text-emerald-600">
            <i class="fa-solid fa-circle-notch fa-spin text-2xl"></i>
        </div>
        <h3 class="mt-5 font-display text-2xl font-black text-slate-950">Memproses pendaftaran</h3>
        <p class="mt-2 text-sm text-slate-600">Sistem sedang mengecek duplikasi, kuota, dan membuat tiket QR.</p>
    </div>
</div>

<script>
window.EventinPageData = {
    eventTitle: <?= json_encode($event['judul']) ?>,
    eventUrl: <?= json_encode($currentUrl) ?>
};
</script>
<script src="<?= $baseUrl ?>/js/pages/event-detail.js"></script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
