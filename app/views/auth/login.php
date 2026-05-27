<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<section class="eventin-container py-10 lg:py-16">
    <div class="grid min-h-[72vh] overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lift lg:grid-cols-[1.05fr_.95fr]">
        <div class="relative hidden bg-slate-950 p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="shape-grid absolute inset-0 opacity-20"></div>
            <div class="relative">
                <span class="badge-soft !border-white/10 !bg-white/10 !text-lime-200">Akun Eventin</span>
                <h1 class="mt-8 font-display text-6xl font-black leading-none">Masuk ke tiket seminar Anda.</h1>
                <p class="mt-6 max-w-md text-lg leading-8 text-slate-300">Kelola tiket, kehadiran, sertifikat, dan data seminar dari satu akun Eventin.</p>
            </div>
        </div>

        <div class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                <a href="<?= $baseUrl ?>/" class="mb-8 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <span class="badge-soft">Login</span>
                <h2 class="mt-4 font-display text-4xl font-black text-slate-950">Selamat datang kembali.</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Masuk untuk membuka tiket digital, dashboard, dan operasi event.</p>

                <?php if (!empty($error)): ?>
                    <div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div>
                    <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div>
                    <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
                        <i class="fa-solid fa-circle-check mr-2"></i><?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form data-loading-submit class="mt-8 space-y-5" action="<?= $baseUrl ?>/login<?= !empty($redirect) ? '?redirect=' . urlencode($redirect) : '' ?>" method="POST">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-black text-slate-700">Email</label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="email" name="email" type="email" required autocomplete="email" placeholder="nama@email.com" class="input-modern pl-11">
                        </div>
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-black text-slate-700">Password</label>
                            <a href="#" class="text-xs font-black text-emerald-600 hover:text-emerald-700">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Masukkan password" class="input-modern pl-11 pr-12">
                            <button type="button" data-password-toggle="#password" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm font-bold text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500">
                            Remember me
                        </label>
                        <span class="text-xs font-bold text-slate-400">Protected session</span>
                    </div>
                    <button type="submit" class="btn-accent w-full"><i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk</button>
                </form>

                <p class="mt-7 text-center text-sm font-semibold text-slate-600">
                    Belum punya akun?
                    <a href="<?= $baseUrl ?>/register" class="font-black text-slate-950 hover:text-emerald-600">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>