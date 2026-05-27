<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<section class="eventin-container py-10 lg:py-16">
    <div class="grid overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lift lg:grid-cols-[.9fr_1.1fr]">
        <aside class="relative bg-gradient-to-br from-lime-100 via-white to-indigo-50 p-8 sm:p-10">
            <span class="badge-soft">Create Participant Pass</span>
            <h1 class="mt-6 font-display text-5xl font-black leading-none text-slate-950">Daftar, dapatkan QR, hadir tanpa ribet.</h1>
            <p class="mt-5 max-w-md leading-7 text-slate-600">Akun peserta menyimpan tiket digital, riwayat event, status attendance, dan e-certificate.</p>
            <div class="mt-8 space-y-3">
                <div class="bento-card p-4"><i class="fa-solid fa-qrcode mr-2 text-emerald-500"></i><span class="font-black text-slate-950">QR tiket otomatis setelah registrasi event.</span></div>
                <div class="bento-card p-4"><i class="fa-solid fa-award mr-2 text-orange-400"></i><span class="font-black text-slate-950">Sertifikat tersedia setelah check-in.</span></div>
                <div class="bento-card p-4"><i class="fa-solid fa-mobile-screen mr-2 text-indigo-500"></i><span class="font-black text-slate-950">Mobile-first untuk antrean masuk.</span></div>
            </div>
        </aside>

        <div class="p-6 sm:p-10">
            <div class="mx-auto max-w-2xl">
                <a href="<?= $baseUrl ?>/" class="mb-8 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <h2 class="font-display text-4xl font-black text-slate-950">Buat akun Eventin</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Isi data resmi peserta untuk tiket dan sertifikat.</p>

                <?php if (!empty($error)): ?>
                    <div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div>
                    <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form id="register-form" data-loading-submit class="mt-8 grid gap-5 sm:grid-cols-2" action="<?= $baseUrl ?>/register" method="POST">
                    <div class="sm:col-span-2">
                        <label for="nama" class="mb-2 block text-sm font-black text-slate-700">Nama lengkap</label>
                        <input id="nama" name="nama" type="text" required placeholder="Nama sesuai sertifikat" class="input-modern" data-validate="min" data-min="3">
                        <p class="mt-1 hidden text-xs font-bold text-red-500" data-error-for="nama">Nama minimal 3 karakter.</p>
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-black text-slate-700">Email</label>
                        <input id="email" name="email" type="email" required placeholder="nama@email.com" class="input-modern" data-validate="email">
                        <p class="mt-1 hidden text-xs font-bold text-red-500" data-error-for="email">Format email belum valid.</p>
                    </div>
                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-black text-slate-700">No HP</label>
                        <input id="no_hp" name="no_hp" type="tel" required placeholder="0812XXXXXXXX" class="input-modern" data-validate="phone">
                        <p class="mt-1 hidden text-xs font-bold text-red-500" data-error-for="no_hp">Nomor HP minimal 8 digit.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="instansi" class="mb-2 block text-sm font-black text-slate-700">Instansi</label>
                        <input id="instansi" name="instansi" type="text" required placeholder="Universitas / Perusahaan / Komunitas" class="input-modern" data-validate="min" data-min="2">
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-black text-slate-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required placeholder="Minimal 6 karakter" class="input-modern pr-12" data-validate="password">
                            <button type="button" data-password-toggle="#password" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100"><i class="fa-regular fa-eye"></i></button>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div id="password-strength" class="h-full w-0 rounded-full bg-red-500 transition-all"></div></div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-black text-slate-700">Konfirmasi password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Ulangi password" class="input-modern pr-12" data-validate="confirm">
                            <button type="button" data-password-toggle="#password_confirmation" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100"><i class="fa-regular fa-eye"></i></button>
                        </div>
                        <p class="mt-1 hidden text-xs font-bold text-red-500" data-error-for="password_confirmation">Konfirmasi password belum sama.</p>
                    </div>
                    <div class="sm:col-span-2 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold leading-5 text-emerald-700">
                        <i class="fa-solid fa-shield-halved mr-2"></i>Data ini akan dipakai untuk tiket digital dan sertifikat event.
                    </div>
                    <button id="register-submit" type="submit" class="btn-accent sm:col-span-2"><i class="fa-solid fa-user-plus"></i> Daftar Sekarang</button>
                </form>
                <p class="mt-7 text-center text-sm font-semibold text-slate-600">
                    Sudah punya akun?
                    <a href="<?= $baseUrl ?>/login" class="font-black text-slate-950 hover:text-emerald-600">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/register.js"></script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
