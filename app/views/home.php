<?php include __DIR__ . '/layouts/header.php'; ?>

<?php
$featuredEvent = $events[0] ?? null;
$totalSeats = array_sum(array_map(fn($event) => (int)$event['kuota'], $events));
$totalRegistered = array_sum(array_map(fn($event) => (int)$event['terdaftar'], $events));
$nextEventDate = $featuredEvent ? $featuredEvent['tanggal_event'] . ' ' . $featuredEvent['waktu_event'] : date('Y-m-d H:i:s', strtotime('+7 days'));
?>

<section class="relative overflow-hidden">
    <div class="eventin-container py-14 sm:py-20 lg:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[1.02fr_.98fr]">
            <div class="animate-fadeUp">
                <div class="badge-soft mb-5">
                    <i class="fa-solid fa-circle text-[8px] text-emerald-500"></i>
                    EVENTIN - Portal Seminar & Workshop
                </div>
                <h1 class="font-display text-[4rem] font-black leading-[.82] tracking-tight text-slate-950 sm:text-[6rem] lg:text-[7.5rem]">
                    LEARN.<br>CONNECT.<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-lime-500 to-indigo-500">GROW.</span>
                </h1>
                <p class="mt-7 max-w-2xl text-lg leading-8 text-slate-600">
                    Tingkatkan keahlian Anda, perluas jaringan profesional, dan dapatkan sertifikat resmi dari pakar industri terkemuka.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="#events" class="btn-accent"><i class="fa-solid fa-calendar-days"></i> Jelajahi Event</a>
                    <?php if (!$isLoggedIn): ?>
                        <a href="<?= $baseUrl ?>/register" class="btn-secondary"><i class="fa-solid fa-user-plus"></i> Buat Akun</a>
                    <?php else: ?>
                        <a href="<?= $isAdmin ? $baseUrl . '/admin' : $baseUrl . '/dashboard' ?>" class="btn-secondary"><i class="fa-solid fa-grid-2"></i> Buka Dashboard</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="relative shape-grid rounded-[2rem] border border-slate-200 bg-white/50 p-3 shadow-soft">
                <div class="absolute -left-4 top-8 hidden rotate-[-7deg] rounded-2xl border border-lime-200 bg-lime-100 px-4 py-2 text-xs font-black uppercase text-lime-900 shadow-soft sm:block">Seminar Aktif</div>
                <div class="absolute -right-3 bottom-10 hidden rotate-[5deg] rounded-2xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-black uppercase text-indigo-700 shadow-soft sm:block">Tiket QR</div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="bento-card p-6 sm:col-span-2">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase text-slate-400">Ringkasan Seminar</p>
                                <h2 class="mt-2 font-display text-2xl font-black text-slate-950">Tiket & Kehadiran</h2>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-lime-300">
                                <i class="fa-solid fa-wave-square"></i>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-3 gap-3">
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-2xl font-black text-slate-950"><?= count($events) ?></p>
                                <p class="text-xs font-bold text-slate-500">Event Open</p>
                            </div>
                            <div class="rounded-3xl bg-emerald-50 p-4">
                                <p class="text-2xl font-black text-emerald-600"><?= $totalRegistered ?></p>
                                <p class="text-xs font-bold text-emerald-700">Terdaftar</p>
                            </div>
                            <div class="rounded-3xl bg-indigo-50 p-4">
                                <p class="text-2xl font-black text-indigo-600"><?= max(0, $totalSeats - $totalRegistered) ?></p>
                                <p class="text-xs font-bold text-indigo-700">Sisa Kursi</p>
                            </div>
                        </div>
                    </div>
                    <div class="bento-card p-6">
                        <p class="text-xs font-black uppercase text-slate-400">Countdown Event</p>
                        <div class="mt-4 grid grid-cols-2 gap-2" data-countdown="<?= htmlspecialchars($nextEventDate) ?>">
                            <div class="rounded-2xl bg-slate-950 p-3 text-center text-white"><b class="text-2xl" data-days>00</b><span class="block text-[10px] font-bold uppercase text-slate-400">Hari</span></div>
                            <div class="rounded-2xl bg-slate-100 p-3 text-center"><b class="text-2xl text-slate-950" data-hours>00</b><span class="block text-[10px] font-bold uppercase text-slate-500">Jam</span></div>
                            <div class="rounded-2xl bg-lime-100 p-3 text-center"><b class="text-2xl text-lime-900" data-minutes>00</b><span class="block text-[10px] font-bold uppercase text-lime-800">Menit</span></div>
                            <div class="rounded-2xl bg-emerald-100 p-3 text-center"><b class="text-2xl text-emerald-700" data-seconds>00</b><span class="block text-[10px] font-bold uppercase text-emerald-700">Detik</span></div>
                        </div>
                    </div>
                    <div class="bento-card overflow-hidden">
                        <?php if ($featuredEvent && !empty($featuredEvent['banner']) && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $featuredEvent['banner'])): ?>
                            <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($featuredEvent['banner']) ?>" alt="<?= htmlspecialchars($featuredEvent['judul']) ?>" class="h-full min-h-[230px] w-full object-cover">
                        <?php else: ?>
                            <div class="flex min-h-[230px] flex-col justify-between bg-gradient-to-br from-slate-950 via-emerald-950 to-indigo-950 p-6 text-white">
                                <i class="fa-solid fa-ticket text-3xl text-lime-300"></i>
                                <div>
                                    <p class="text-xs font-black uppercase text-emerald-200">Akses Seminar</p>
                                    <p class="mt-2 font-display text-2xl font-black">E-Tiket QR Instan</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Event Listing Section -->
<section id="events" class="eventin-container py-14">

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <span class="badge-soft">Upcoming Event</span>
            <h2 class="mt-3 font-display text-4xl font-black text-slate-950">Event Unggulan</h2>
            <p class="mt-2 max-w-2xl text-slate-600">Cari konferensi, workshop, dan sesi teknologi yang sedang dibuka.</p>
        </div>
        <div class="grid gap-3 sm:grid-cols-[1fr_auto] lg:w-[520px]">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="event-search" class="input-modern pl-11" placeholder="Cari event, lokasi, atau topik...">
            </div>
            <select id="event-filter" class="input-modern sm:w-44">
                <option value="all">Semua Status</option>
                <option value="available">Kuota Tersedia</option>
                <option value="limited">Hampir Penuh</option>
            </select>
        </div>
    </div>

    <?php if (empty($events)): ?>
        <div class="premium-card rounded-[2rem] p-10 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-lime-100 text-lime-800"><i class="fa-regular fa-calendar"></i></div>
            <h3 class="mt-5 font-display text-2xl font-black text-slate-950">Belum Ada Event</h3>
            <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">Event baru akan tampil di sini begitu admin membuka pendaftaran.</p>
        </div>
    <?php else: ?>
        <div id="event-grid" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($events as $index => $event): ?>
                <?php
                    $tanggal = date('d M Y', strtotime($event['tanggal_event']));
                    $waktu = date('H:i', strtotime($event['waktu_event']));
                    $sisaKuota = max(0, (int)$event['kuota'] - (int)$event['terdaftar']);
                    $kuotaPersen = $event['kuota'] > 0 ? min(100, ($event['terdaftar'] / $event['kuota']) * 100) : 0;
                    $availability = $kuotaPersen >= 80 ? 'limited' : 'available';
                ?>
                <article class="event-card bento-card overflow-hidden" data-title="<?= strtolower(htmlspecialchars($event['judul'] . ' ' . $event['lokasi'] . ' ' . $event['deskripsi'])) ?>" data-status="<?= $availability ?>">
                    <div class="relative h-52 overflow-hidden bg-slate-100">
                        <?php if (!empty($event['banner']) && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $event['banner'])): ?>
                            <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($event['banner']) ?>" alt="<?= htmlspecialchars($event['judul']) ?>" class="h-full w-full object-cover transition duration-500 hover:scale-105">
                        <?php else: ?>
                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-950 via-emerald-950 to-indigo-950 text-lime-200">
                                <i class="fa-solid fa-calendar-days text-5xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute left-4 top-4 flex gap-2">
                            <span class="rounded-full bg-white/85 px-3 py-1 text-[11px] font-black uppercase text-slate-950 backdrop-blur">Featured</span>
                            <span class="rounded-full bg-emerald-500 px-3 py-1 text-[11px] font-black uppercase text-white">Open</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 text-xs font-black uppercase text-slate-500">
                            <span><i class="fa-regular fa-calendar mr-1"></i><?= $tanggal ?></span>
                            <span class="text-slate-300">/</span>
                            <span><?= $waktu ?> WIB</span>
                        </div>
                        <h3 class="mt-3 font-display text-2xl font-black text-slate-950 line-clamp-2"><?= htmlspecialchars($event['judul']) ?></h3>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600"><?= htmlspecialchars(strip_tags($event['deskripsi'])) ?></p>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-slate-600">
                            <i class="fa-solid fa-location-dot text-emerald-500"></i>
                            <span class="line-clamp-1"><?= htmlspecialchars($event['lokasi']) ?></span>
                        </div>
                        <div class="mt-5">
                            <div class="mb-2 flex items-center justify-between text-xs font-black uppercase text-slate-500">
                                <span>Kuota</span>
                                <span><?= $sisaKuota ?> tersisa</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-lime-400 to-emerald-500 w-[<?= $kuotaPersen ?>%]"></div>
                            </div>
                        </div>
                        <a href="<?= $baseUrl ?>/event/<?= htmlspecialchars($event['slug']) ?>" class="btn-primary mt-6 w-full">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div id="event-empty" class="hidden premium-card rounded-[2rem] p-10 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-orange-100 text-orange-500"><i class="fa-solid fa-magnifying-glass"></i></div>
            <h3 class="mt-5 font-display text-xl font-black text-slate-950">Tidak Ada Hasil</h3>
            <p class="mt-2 text-sm text-slate-600">Coba kata kunci atau filter lain.</p>
        </div>
    <?php endif; ?>
</section>


<section class="eventin-container py-14">
    <div class="premium-card rounded-[2rem] p-6 sm:p-8">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <div>
                <span class="badge-soft">FAQ</span>
                <h2 class="mt-4 font-display text-3xl font-black text-slate-950">Pertanyaan Umum</h2>
            </div>
            <div class="space-y-3">
                <?php
                $faqs = [
                    ['Apakah tiket langsung dibuat?', 'Ya. Setelah pendaftaran sukses, sistem membuat kode tiket dan QR digital otomatis.'],
                    ['Bagaimana check-in dilakukan?', 'Admin membuka QR Scanner, mengarahkan kamera ke QR tiket, lalu sistem memvalidasi token ke database.'],
                    ['Kapan sertifikat tersedia?', 'Sertifikat tersedia setelah status attendance peserta berubah menjadi hadir.'],
                ];
                foreach ($faqs as $faq):
                ?>
                    <details class="rounded-3xl border border-slate-200 bg-white p-5">
                        <summary class="cursor-pointer font-black text-slate-950"><?= htmlspecialchars($faq[0]) ?></summary>
                        <p class="mt-3 text-sm leading-6 text-slate-600"><?= htmlspecialchars($faq[1]) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/home.js"></script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
