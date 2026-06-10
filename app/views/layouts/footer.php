    </main>

    <?php if (!str_contains(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/admin/scan')): ?>
        <footer class="relative z-10 mt-20 border-t border-slate-200/80 bg-white/70 dark:bg-slate-900/70 dark:border-slate-800/80 backdrop-blur-xl">
            <div class="eventin-container py-12 md:py-16">
                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                    <!-- Brand Column -->
                    <div class="lg:col-span-1">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="h-8 w-8 text-slate-900 dark:text-white">
                                <defs>
                                    <linearGradient id="logo-footer-grad-new" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="var(--theme-light-hex-300)" />
                                        <stop offset="100%" stop-color="var(--theme-dark-hex-600)" />
                                    </linearGradient>
                                </defs>
                                <path d="M 25 55 H 75 A 25 25 0 0 0 25 55 A 25 25 0 0 0 70 70" fill="none" stroke="url(#logo-footer-grad-new)" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="font-display text-2xl font-black text-slate-900 dark:text-white tracking-wide">Eventin</span>
                        </div>
                        <p class="mt-4 text-sm font-bold uppercase text-slate-500">Tiket Seminar Online</p>
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-600 dark:text-slate-400">
                            Aplikasi seminar untuk pendaftaran peserta, tiket QR, check-in, sertifikat digital, dan laporan kehadiran.
                        </p>
                    </div>
                    
                    <!-- Navigasi -->
                    <div>
                        <h3 class="mb-5 text-sm font-bold text-slate-900 dark:text-white">Navigasi Utama</h3>
                        <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                            <li><a href="<?= $baseUrl ?>/" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Event Aktif</a></li>
                            <li><a href="<?= $baseUrl ?>/dashboard" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Tiket Saya</a></li>
                            <li><a href="<?= $baseUrl ?>/admin/scan" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">QR Scanner</a></li>
                            <li><a href="#" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Sertifikat Digital</a></li>
                        </ul>
                    </div>
                    
                    <!-- Informasi -->
                    <div>
                        <h3 class="mb-5 text-sm font-bold text-slate-900 dark:text-white">Informasi Peserta</h3>
                        <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                            <li><a href="<?= $baseUrl ?>/panduan-pendaftaran" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Panduan Pendaftaran</a></li>
                            <li><a href="<?= $baseUrl ?>/cara-check-in" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Cara Check-In QR</a></li>
                            <li><a href="<?= $baseUrl ?>/syarat-ketentuan" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Syarat & Ketentuan</a></li>
                            <li><a href="<?= $baseUrl ?>/faq" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">FAQ</a></li>
                            <li><a href="<?= $baseUrl ?>/validasi-sertifikat" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Validasi Sertifikat</a></li>
                        </ul>
                    </div>
                    
                    <!-- Kategori Event -->
                    <div>
                        <h3 class="mb-5 text-sm font-bold text-slate-900 dark:text-white">Kategori Seminar</h3>
                        <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                            <li><a href="#" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Teknologi & IT</a></li>
                            <li><a href="#" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Bisnis & Karir</a></li>
                            <li><a href="#" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Desain & Kreatif</a></li>
                            <li><a href="#" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Edukasi & Akademik</a></li>
                            <li><a href="#" class="hover:text-emerald-500 dark:hover:text-emerald-400 transition">Semua Kategori</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-12 md:mt-16 border-t border-slate-200 dark:border-slate-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">&copy; <?= date('Y') ?> Eventin. Hak Cipta Dilindungi.</p>
                    <div class="flex items-center gap-4 text-slate-400 text-lg">
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $baseUrl ?>/js/app.js"></script>
</body>
</html>
