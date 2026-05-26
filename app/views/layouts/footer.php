    </main>

    <?php if (!str_contains(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/admin/scan')): ?>
        <footer class="relative z-10 mt-20 border-t border-slate-200/80 bg-white/70 backdrop-blur-xl">
            <div class="eventin-container py-10">
                <div class="grid gap-8 lg:grid-cols-[1.3fr_.7fr_.7fr]">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-950 text-lime-300">
                                <i class="fa-solid fa-bolt-lightning"></i>
                            </span>
                            <div>
                                <p class="font-display text-lg font-black text-slate-950">EVENTIN</p>
                                <p class="text-xs font-bold uppercase text-slate-500">Tiket Seminar Online</p>
                            </div>
                        </div>
                        <p class="mt-4 max-w-md text-sm leading-6 text-slate-600">
                            Aplikasi seminar untuk pendaftaran peserta, tiket QR, check-in, sertifikat digital, dan laporan kehadiran.
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-slate-400">Menu</p>
                        <div class="mt-4 space-y-3 text-sm font-bold text-slate-600">
                            <a class="block hover:text-slate-950" href="<?= $baseUrl ?>/">Event Aktif</a>
                            <a class="block hover:text-slate-950" href="<?= $baseUrl ?>/dashboard">Tiket Saya</a>
                            <a class="block hover:text-slate-950" href="<?= $baseUrl ?>/admin/scan">QR Scanner</a>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-slate-400">Status</p>
                        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-extrabold text-slate-900">Seminar Aktif</span>
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                            <p class="mt-2 text-xs leading-5 text-slate-500">Siap digunakan untuk registrasi dan check-in peserta seminar.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs font-semibold text-slate-500">&copy; <?= date('Y') ?> Eventin. Tiket seminar dan check-in peserta.</p>
                    <div class="flex gap-2 text-slate-500">
                        <a class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white hover:text-slate-950" href="#"><i class="fa-brands fa-github"></i></a>
                        <a class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white hover:text-slate-950" href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white hover:text-slate-950" href="#"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <script src="<?= $baseUrl ?>/js/app.js"></script>
</body>
</html>
