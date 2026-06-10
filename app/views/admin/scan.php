<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>


<section class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1500px]">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="badge-soft !border-emerald-400/20 !bg-emerald-400/10 !text-emerald-300">Scanner Tiket Seminar</span>
                <h1 class="mt-3 font-display text-3xl font-black text-white sm:text-5xl">Scan Kehadiran Peserta</h1>
                <p class="mt-2 max-w-2xl text-slate-400">Arahkan QR tiket seminar ke kamera untuk mencatat kehadiran peserta.</p>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <select id="camera-select" class="hidden w-full sm:w-auto max-w-[280px] sm:max-w-sm text-ellipsis overflow-hidden rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-bold text-white outline-none"></select>
                <button id="toggle-cam-btn" onclick="toggleScanner()" class="btn-accent w-full sm:w-auto whitespace-nowrap"><i class="fa-solid fa-camera"></i> Mulai Kamera</button>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,880px)_390px] xl:justify-center">
            <div class="scanner-panel rounded-[2rem] p-4">
                <div id="scanner-shell" class="scanner-frame relative overflow-hidden rounded-[1.75rem] border border-emerald-400/20 bg-slate-950 aspect-[4/3] sm:aspect-video flex items-center justify-center">
                    <div id="reader-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-4 text-center z-10">
                        <div class="flex h-24 w-24 items-center justify-center rounded-[2rem] border border-emerald-400/20 bg-emerald-400/10 text-emerald-300 shadow-glow">
                            <i class="fa-solid fa-qrcode text-4xl"></i>
                        </div>
                        <div class="px-4">
                            <p class="font-display text-2xl font-black text-white">Scanner standby</p>
                            <p class="mt-2 text-sm font-bold text-slate-400 max-w-[250px] mx-auto">Klik mulai kamera untuk membaca QR tiket.</p>
                        </div>
                    </div>
                    <div id="reader" class="h-full w-full"></div>
                    <div class="pointer-events-none absolute inset-x-[16%] top-1/2 h-1 rounded-full bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-glow animate-scanner hidden z-20" id="scan-line"></div>
                    <div id="scan-overlay" class="pointer-events-none absolute inset-0 hidden items-center justify-center bg-slate-950/80 backdrop-blur-sm z-30">
                        <div id="scan-overlay-card" class="rounded-[2rem] border border-emerald-400/30 bg-slate-900 p-8 text-center shadow-glow">
                            <div id="overlay-icon" class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-emerald-400/10 text-emerald-400"><i class="fa-solid fa-circle-check text-4xl"></i></div>
                            <h2 id="overlay-title" class="mt-5 font-display text-3xl font-black text-white">Valid</h2>
                            <p id="overlay-message" class="mt-2 text-sm font-bold text-slate-300">Check-in berhasil.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-2">
                    <div class="flex items-center gap-3">
                        <span id="cam-dot" class="h-3 w-3 rounded-full bg-slate-500"></span>
                        <span id="camera-status" class="text-sm font-black text-slate-400">Kamera belum aktif</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-black uppercase text-slate-400">
                        <i class="fa-solid fa-volume-high text-emerald-400"></i> Beep Feedback Aktif
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div id="feedback-card" class="scanner-panel rounded-[2rem] p-6 text-center">
                    <div id="feedback-icon" class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] border border-white/10 bg-white/5 text-slate-400">
                        <i class="fa-solid fa-radar text-3xl"></i>
                    </div>
                    <h3 id="feedback-title" class="mt-5 font-display text-2xl font-black text-white">Menunggu QR Code</h3>
                    <p id="feedback-desc" class="mt-2 text-sm leading-6 text-slate-400">Scanner akan menampilkan status valid, invalid, atau duplikat setelah QR terbaca.</p>
                    <div id="feedback-details" class="mt-5 hidden rounded-[1.5rem] border border-white/10 bg-white/5 p-4 text-left text-sm">
                        <p class="text-xs font-black uppercase text-slate-500">Peserta</p>
                        <p id="det-name" class="mt-1 font-black text-white"></p>
                        <p class="mt-4 text-xs font-black uppercase text-slate-500">Event</p>
                        <p id="det-event" class="mt-1 font-bold text-emerald-300"></p>
                        <p class="mt-4 text-xs font-black uppercase text-slate-500">Waktu</p>
                        <p id="det-time" class="mt-1 font-bold text-slate-300"></p>
                    </div>
                    <p id="reset-timer" class="mt-5 hidden text-xs font-black uppercase text-emerald-400">Scan berikutnya dalam <span id="reset-secs">3</span>s</p>
                </div>

                <div class="scanner-panel rounded-[2rem] p-6">
                    <div class="flex items-center justify-between">
                        <span class="badge-soft !border-white/10 !bg-white/5 !text-slate-300">History Scan</span>
                        <button onclick="clearHistory()" class="text-xs font-black uppercase text-slate-500 hover:text-white transition-colors">Clear</button>
                    </div>
                    <div id="scan-history" class="mt-5 space-y-3">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-bold text-slate-400 text-center">Belum ada scan pada sesi ini.</div>
                    </div>
                </div>

                <div class="scanner-panel rounded-[2rem] p-6">
                    <span class="badge-soft !border-white/10 !bg-white/5 !text-slate-300">Status Rules</span>
                    <div class="mt-5 grid gap-3 text-sm font-bold">
                        <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-3 text-emerald-300 flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> Valid = check-in hijau</div>
                        <div class="rounded-2xl border border-red-400/20 bg-red-400/10 p-3 text-red-300 flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> Invalid = tiket tidak terdaftar</div>
                        <div class="rounded-2xl border border-orange-400/20 bg-orange-400/10 p-3 text-orange-300 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Duplicate = tiket sudah dipakai</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<script>
window.EventinPageData = {
    baseUrl: '<?= $baseUrl ?>'
};
</script>
<script src="<?= $baseUrl ?>/js/pages/scan.js"></script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
