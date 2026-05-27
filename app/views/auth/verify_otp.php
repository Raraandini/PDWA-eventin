<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<section class="eventin-container py-10 lg:py-16">
    <div class="grid overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lift lg:grid-cols-2">
        <aside class="relative bg-gradient-to-br from-indigo-100 via-white to-blue-50 p-8 sm:p-10 flex flex-col justify-center">
            <span class="badge-soft">Keamanan Akun</span>
            <h1 class="mt-6 font-display text-5xl font-black leading-none text-slate-950">Verifikasi email Anda.</h1>
            <p class="mt-5 max-w-md leading-7 text-slate-600">Kami telah mengirimkan kode OTP ke email Anda. Silakan masukkan kode tersebut untuk menyelesaikan pendaftaran.</p>
            <div class="mt-8 space-y-3">
                <div class="bento-card p-4"><i class="fa-solid fa-envelope-circle-check mr-2 text-indigo-500"></i><span class="font-black text-slate-950">Email dikirim secara instan.</span></div>
                <div class="bento-card p-4"><i class="fa-solid fa-clock mr-2 text-orange-400"></i><span class="font-black text-slate-950">Berlaku selama 5 menit.</span></div>
            </div>
        </aside>

        <div class="p-6 sm:p-10 flex items-center justify-center">
            <div class="w-full max-w-md">
                <h2 class="font-display text-4xl font-black text-slate-950 text-center">Masukkan OTP</h2>
                
                <?php if (!empty($error)): ?>
                    <div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div>
                    <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700 text-center">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div>
                    <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700 text-center">
                        <i class="fa-solid fa-check-circle mr-2"></i><?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form id="verify-form" class="mt-8 space-y-5" action="<?= $baseUrl ?>/verify-otp" method="POST">
                    <div>
                        <label for="otp" class="mb-2 block text-sm font-black text-slate-700 text-center">Kode 6-Digit</label>
                        <input id="otp" name="otp" type="text" required placeholder="XXXXXX" maxlength="6" pattern="\d{6}" class="input-modern text-center text-3xl font-mono tracking-[0.5em]" autocomplete="one-time-code">
                    </div>
                    
                    <button type="submit" class="btn-accent w-full"><i class="fa-solid fa-shield-check"></i> Verifikasi</button>
                </form>

                <form class="mt-6 text-center" action="<?= $baseUrl ?>/resend-otp" method="POST">
                    <p class="text-sm font-semibold text-slate-600 mb-2">Belum menerima email?</p>
                    <button type="submit" class="font-black text-indigo-600 hover:text-indigo-800 underline">Kirim ulang OTP</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
