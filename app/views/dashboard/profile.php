<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>
<?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

<section class="eventin-container py-8">
    <div class="mb-8">

        <h1 class="mt-3 font-display text-4xl font-black text-slate-950 dark:text-white">Pengaturan Profil</h1>
        <p class="mt-2 text-slate-600 dark:text-slate-300">Perbarui informasi pribadi, preview avatar, dan password akun.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <aside class="space-y-6">
            <div class="bento-card p-6 text-center">
                <div id="avatar-preview" class="mx-auto flex h-28 w-28 items-center justify-center rounded-[2rem] text-5xl font-black text-slate-950 shadow-soft overflow-hidden" style="background: linear-gradient(135deg, var(--theme-light-hex-300), var(--theme-dark-hex-500));">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="h-full w-full object-cover">
                    <?php else: ?>
                        <?= htmlspecialchars(mb_substr($user['nama'] ?? $userName, 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <h2 class="mt-5 font-display text-2xl font-black text-slate-950 dark:text-white"><?= htmlspecialchars($user['nama'] ?? $userName) ?></h2>
                <p class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                
                <div class="mt-5">
                    <p class="mb-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pilih Avatar</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <?php 
                        $avatars = ['Felix', 'Luna', 'Bella', 'Simba', 'Oliver', 'Milo', 'Chloe', 'Leo', 'Mia', 'Max'];
                        $currentAvatar = $user['avatar'] ?? '';
                        foreach ($avatars as $seed): 
                            $avatarUrl = "https://api.dicebear.com/9.x/fun-emoji/svg?seed=" . $seed . "&backgroundColor=transparent";
                            $isActive = $currentAvatar === $avatarUrl;
                        ?>
                        <button type="button" 
                                style="background: linear-gradient(135deg, var(--theme-light-hex-300), var(--theme-dark-hex-500));"
                                onclick="document.getElementById('form-avatar-input').value = '<?= $avatarUrl ?>'; document.getElementById('avatar-preview').innerHTML = '<img src=\'<?= $avatarUrl ?>\' class=\'h-full w-full object-cover\'>'; document.querySelectorAll('.avatar-btn').forEach(b => b.classList.remove('ring-4', 'ring-slate-900', 'scale-110')); this.classList.add('ring-4', 'ring-slate-900', 'scale-110');"
                                class="avatar-btn h-10 w-10 overflow-hidden rounded-xl p-1 transition hover:scale-110 <?= $isActive ? 'ring-4 ring-slate-900 scale-110' : '' ?>">
                            <img src="<?= $avatarUrl ?>" alt="Avatar <?= $seed ?>" class="h-full w-full object-contain">
                        </button>
                        <?php endforeach; ?>
                    </div>

                    <p class="mb-3 mt-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pilih Tema Warna</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <?php 
                        $themes = ['lime', 'pink', 'blue', 'purple', 'orange', 'red', 'teal', 'yellow', 'gray', 'fuchsia'];
                        $currentTheme = $user['theme_color'] ?? 'lime';
                        $themeColors = \App\Helpers\ThemeHelper::getThemeColors();
                        foreach ($themes as $theme): 
                            $isActive = $currentTheme === $theme;
                            $colorHex = $themeColors[$theme]['light']['400'];
                        ?>
                        <button type="button" 
                                style="background-color: <?= $colorHex ?>;"
                                onclick="document.getElementById('form-theme-input').value = '<?= $theme ?>'; document.querySelectorAll('.theme-btn').forEach(b => b.classList.remove('ring-4', 'ring-slate-900', 'scale-110')); this.classList.add('ring-4', 'ring-slate-900', 'scale-110');"
                                class="theme-btn h-8 w-8 rounded-full transition hover:scale-110 <?= $isActive ? 'ring-4 ring-slate-900 scale-110' : '' ?>">
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="bento-card p-6">
                <span class="badge-soft">History Event</span>
                <div class="mt-4 space-y-3">
                    <?php if (empty($pendaftarans)): ?>
                        <p class="text-sm font-bold text-slate-500">Belum ada riwayat event.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($pendaftarans, 0, 4) as $item): ?>
                            <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
                                <p class="font-black text-slate-950 dark:text-white"><?= htmlspecialchars($item['nama_event']) ?></p>
                                <p class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400"><?= date('d M Y', strtotime($item['tanggal_event'])) ?> / <?= htmlspecialchars($item['status_checkin']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <form data-loading-submit action="<?= $baseUrl ?>/profile/update" method="POST" class="premium-card rounded-[2rem] p-6 sm:p-8">
            <?= \App\Helpers\AuthHelper::getCsrfInput() ?>
            <input type="hidden" name="avatar" id="form-avatar-input" value="<?= htmlspecialchars($user['avatar'] ?? '') ?>">
            <input type="hidden" name="theme_color" id="form-theme-input" value="<?= htmlspecialchars($user['theme_color'] ?? 'lime') ?>">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <span class="badge-soft">Informasi Pribadi</span>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="nama">Nama</label>
                    <input class="input-modern" id="nama" name="nama" required value="<?= htmlspecialchars($user['nama'] ?? '') ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="email">Email</label>
                    <input class="input-modern" id="email" name="email" type="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="no_hp">No HP</label>
                    <input class="input-modern" id="no_hp" name="no_hp" required value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="instansi">Instansi</label>
                    <input class="input-modern" id="instansi" name="instansi" required value="<?= htmlspecialchars($user['instansi'] ?? '') ?>">
                </div>
                <div class="sm:col-span-2 mt-4 border-t border-slate-200 dark:border-slate-800 pt-6">
                    <span class="badge-soft">Update Password</span>
                    <p class="mt-2 text-sm font-bold text-slate-500 dark:text-slate-400">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="password">Password Baru</label>
                    <div class="relative">
                        <input class="input-modern pr-12" id="password" name="password" type="password" placeholder="Minimal 6 karakter">
                        <button type="button" data-password-toggle="#password" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="password_confirmation">Konfirmasi Password</label>
                    <div class="relative">
                        <input class="input-modern pr-12" id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password">
                        <button type="button" data-password-toggle="#password_confirmation" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end border-t border-slate-200 dark:border-slate-800 pt-6">
                <button type="submit" class="btn-accent"><i class="fa-solid fa-floppy-disk"></i> Simpan Profil</button>
            </div>
        </form>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/profile.js"></script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
