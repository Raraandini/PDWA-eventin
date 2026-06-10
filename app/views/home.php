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
                <h1 class="font-display text-[4rem] font-black leading-[.82] tracking-tight text-slate-950 dark:text-white sm:text-[6rem] lg:text-[7.5rem]">
                    LEARN.<br>CONNECT.<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-lime-500 to-indigo-500">GROW.</span>
                </h1>
                <p class="mt-7 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">
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
                                <h2 class="mt-2 font-display text-2xl font-black text-slate-950 dark:text-white">Tiket & Kehadiran</h2>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-lime-300">
                                <i class="fa-solid fa-wave-square"></i>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-3 gap-3">
                            <div class="rounded-3xl bg-slate-50 dark:bg-slate-800 p-4">
                                <p class="text-2xl font-black text-slate-950 dark:text-white"><?= count($events) ?></p>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Event Open</p>
                            </div>
                            <div class="rounded-3xl bg-emerald-50 dark:bg-emerald-900/30 p-4">
                                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400"><?= $totalRegistered ?></p>
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-500">Terdaftar</p>
                            </div>
                            <div class="rounded-3xl bg-indigo-50 dark:bg-indigo-900/30 p-4">
                                <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400"><?= max(0, $totalSeats - $totalRegistered) ?></p>
                                <p class="text-xs font-bold text-indigo-700 dark:text-indigo-500">Sisa Kursi</p>
                            </div>
                        </div>
                    </div>
                    <div class="bento-card p-6">
                        <p class="text-xs font-black uppercase text-slate-400">Countdown Event</p>
                        <div class="mt-4 grid grid-cols-2 gap-2" data-countdown="<?= htmlspecialchars($nextEventDate) ?>">
                            <div class="rounded-2xl bg-slate-950 p-3 text-center text-white"><b class="text-2xl" data-days>00</b><span class="block text-[10px] font-bold uppercase text-slate-400">Hari</span></div>
                            <div class="rounded-2xl bg-slate-100 dark:bg-slate-800 p-3 text-center"><b class="text-2xl text-slate-950 dark:text-white" data-hours>00</b><span class="block text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400">Jam</span></div>
                            <div class="rounded-2xl bg-lime-100 dark:bg-lime-900/30 p-3 text-center"><b class="text-2xl text-lime-900 dark:text-lime-400" data-minutes>00</b><span class="block text-[10px] font-bold uppercase text-lime-800 dark:text-lime-500">Menit</span></div>
                            <div class="rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 p-3 text-center"><b class="text-2xl text-emerald-700 dark:text-emerald-400" data-seconds>00</b><span class="block text-[10px] font-bold uppercase text-emerald-700 dark:text-emerald-500">Detik</span></div>
                        </div>
                    </div>
                    <div class="bento-card overflow-hidden">
                        <?php 
                            $hasFeaturedBanner = $featuredEvent && !empty($featuredEvent['banner']) && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $featuredEvent['banner']);
                            $featuredBannerSrc = $hasFeaturedBanner ? $baseUrl . '/uploads/' . htmlspecialchars($featuredEvent['banner']) : \App\Helpers\BannerHelper::generateSvgImageUri($featuredEvent);
                        ?>
                        <img src="<?= $featuredBannerSrc ?>" alt="<?= htmlspecialchars($featuredEvent['judul']) ?>" class="h-full min-h-[230px] w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promo Banner Marquee -->
<section class="py-8 bg-slate-50 dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800 overflow-hidden relative">
    <div class="flex gap-6 w-max animate-marquee px-4">
        <!-- We repeat the images to create the infinite scrolling effect -->
        <img src="<?= $baseUrl ?>/images/promo-banner-4.png" alt="Eventin Promo" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner.png" alt="Promo Eventin" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner-2.png" alt="Solusi Tiket" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner-3.png" alt="Informasi Menarik" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        
        <!-- Duplicates for seamless scroll -->
        <img src="<?= $baseUrl ?>/images/promo-banner-4.png" alt="Eventin Promo" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner.png" alt="Promo Eventin" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner-2.png" alt="Solusi Tiket" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner-3.png" alt="Informasi Menarik" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        
        <!-- Second Set of Duplicates for extra wide screens to prevent empty gaps -->
        <img src="<?= $baseUrl ?>/images/promo-banner-4.png" alt="Eventin Promo" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner.png" alt="Promo Eventin" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner-2.png" alt="Solusi Tiket" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
        <img src="<?= $baseUrl ?>/images/promo-banner-3.png" alt="Informasi Menarik" class="h-[160px] sm:h-[220px] md:h-[280px] w-[360px] sm:w-[540px] md:w-[660px] rounded-3xl object-cover shadow-soft flex-shrink-0">
    </div>
</section>

<!-- Event Listing Section -->
<section id="events" class="eventin-container py-14">

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <span class="badge-soft">Upcoming Event</span>
            <h2 class="mt-3 font-display text-4xl font-black text-slate-950 dark:text-white">Event Unggulan</h2>
            <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-300">Cari konferensi, workshop, dan sesi teknologi yang sedang dibuka.</p>
        </div>
        <form action="<?= $baseUrl ?>/#events" method="GET" class="lg:w-[420px]">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="input-modern pl-11" placeholder="Cari event, topik...">
                <?php if (!empty($_GET['kategori'])): ?>
                    <input type="hidden" name="kategori" value="<?= htmlspecialchars($_GET['kategori']) ?>">
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Category Pills -->
    <div class="mb-8 flex flex-wrap gap-2">
        <?php 
        $activeCat = $_GET['kategori'] ?? '';
        $cats = ['Semua', 'Umum', 'Teknologi', 'Bisnis', 'Kesehatan', 'Pendidikan', 'Desain', 'Olahraga', 'Hiburan']; 
        foreach($cats as $c): 
            $val = $c === 'Semua' ? '' : $c;
            $isActive = $activeCat === $val;
            $link = $baseUrl . '/?kategori=' . urlencode($val) . (!empty($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '') . '#events';
        ?>
            <a href="<?= $link ?>" class="rounded-full px-5 py-2 text-sm font-bold transition-all <?= $isActive ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' ?>">
                <?= $c ?>
            </a>
        <?php endforeach; ?>
    </div>
    </div>

    <div id="events-wrapper" class="transition-opacity duration-300">
        <?php if (empty($events)): ?>
            <div id="event-empty" class="premium-card rounded-[2rem] p-10 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-lime-100 dark:bg-lime-900/40 text-lime-800 dark:text-lime-400"><i class="fa-regular fa-calendar"></i></div>
                <h3 class="mt-5 font-display text-2xl font-black text-slate-950 dark:text-white">Belum Ada Event</h3>
                <p class="mx-auto mt-2 max-w-md text-sm text-slate-600 dark:text-slate-400">Event dengan kriteria tersebut tidak ditemukan atau belum dibuka pendaftarannya.</p>
            </div>
        <?php else: ?>
            <div id="event-grid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($events as $index => $event): ?>
                    <?php
                        $tanggal = date('d M Y', strtotime($event['tanggal_event']));
                        $waktu = date('H:i', strtotime($event['waktu_event']));
                        $sisaKuota = max(0, (int)$event['kuota'] - (int)$event['terdaftar']);
                        $kuotaPersen = $event['kuota'] > 0 ? min(100, ($event['terdaftar'] / $event['kuota']) * 100) : 0;
                        $availability = $kuotaPersen >= 80 ? 'limited' : 'available';
                    ?>
                    <article class="reveal-item opacity-0 translate-y-8 transition-all duration-700 ease-out event-card bento-card overflow-hidden flex flex-col group" data-title="<?= strtolower(htmlspecialchars($event['judul'] . ' ' . $event['lokasi'] . ' ' . $event['deskripsi'])) ?>" data-status="<?= $availability ?>">
                        <div class="relative h-32 shrink-0 overflow-hidden bg-slate-100 dark:bg-slate-800">
                            <?php 
                                $hasBanner = !empty($event['banner']) && file_exists(dirname(dirname(__DIR__)) . '/public/uploads/' . $event['banner']);
                                $bannerSrc = $hasBanner ? $baseUrl . '/uploads/' . htmlspecialchars($event['banner']) : \App\Helpers\BannerHelper::generateSvgImageUri($event);
                            ?>
                            <img src="<?= $bannerSrc ?>" alt="<?= htmlspecialchars($event['judul']) ?>" class="h-full w-full object-cover transition duration-500 hover:scale-105">
                            <div class="absolute left-3 top-3 flex gap-1.5">
                                <span class="rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-black uppercase text-slate-950 backdrop-blur">Featured</span>
                                <span class="rounded-full bg-emerald-500 px-2 py-0.5 text-[10px] font-black uppercase text-white">Open</span>
                            </div>
                        </div>
                        <div class="p-4 px-5 flex flex-col flex-1">
                            <div class="flex items-center gap-1.5 text-[11px] font-black uppercase text-slate-500">
                                <span><i class="fa-regular fa-calendar mr-1"></i><?= $tanggal ?></span>
                                <span class="text-slate-300">/</span>
                                <span><?= $waktu ?> WIB</span>
                            </div>
                            <h3 class="mt-1.5 font-display text-[15px] font-black text-slate-950 dark:text-white line-clamp-2 leading-snug"><?= htmlspecialchars($event['judul']) ?></h3>
                            <p class="mt-1 line-clamp-2 text-[12px] leading-relaxed text-slate-600 dark:text-slate-300"><?= htmlspecialchars(strip_tags($event['deskripsi'])) ?></p>
                            <div class="mt-auto pt-2.5 flex items-center gap-1.5 text-[12px] font-bold text-slate-600 dark:text-slate-400">
                                <i class="fa-solid fa-location-dot text-emerald-500"></i>
                                <span class="line-clamp-1"><?= htmlspecialchars($event['lokasi']) ?></span>
                            </div>
                            <div class="pt-3">
                                <div class="mb-1.5 flex items-center justify-between text-[11px] font-black uppercase text-slate-500">
                                    <span>Kuota</span>
                                    <span><?= $sisaKuota ?> tersisa</span>
                                </div>
                                <div class="h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-gradient-to-r from-lime-400 to-emerald-500 w-[<?= $kuotaPersen ?>%]"></div>
                                </div>
                                <a href="<?= $baseUrl ?>/event/<?= htmlspecialchars($event['slug']) ?>" class="btn-primary mt-3.5 w-full py-2 text-sm">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <?php if ($totalPages > 1): ?>
                <div class="mt-12 flex justify-center gap-2" id="pagination-controls">
                    <?php 
                        $basePaginationUrl = $baseUrl . '/?kategori=' . urlencode($_GET['kategori'] ?? '') . (!empty($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '');
                    ?>
                    
                    <?php if ($page > 1): ?>
                        <a href="<?= $basePaginationUrl ?>&page=<?= $page - 1 ?>#events" class="pagination-link flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-950"><i class="fa-solid fa-chevron-left"></i></a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= $basePaginationUrl ?>&page=<?= $i ?>#events" class="pagination-link flex h-10 w-10 items-center justify-center rounded-full shadow-sm transition <?= $i === $page ? 'bg-slate-950 text-white font-black dark:bg-white dark:text-slate-900' : 'bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-950 font-bold dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="<?= $basePaginationUrl ?>&page=<?= $page + 1 ?>#events" class="pagination-link flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-950"><i class="fa-solid fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>


<section class="eventin-container py-14">
    <div class="premium-card rounded-[2rem] p-6 sm:p-8">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <div>
                <span class="badge-soft">FAQ</span>
                <h2 class="mt-4 font-display text-3xl font-black text-slate-950 dark:text-white">Pertanyaan Umum</h2>
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
                    <details class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5">
                        <summary class="cursor-pointer font-black text-slate-950 dark:text-white"><?= htmlspecialchars($faq[0]) ?></summary>
                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300"><?= htmlspecialchars($faq[1]) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/home.js?v=<?= time() . rand() ?>"></script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
