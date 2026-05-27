<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>
<?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

<section class="eventin-container py-8">
    <div class="mb-8">
        <a href="<?= $baseUrl ?>/dashboard" class="mb-3 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
        <span class="badge-soft">Profil Peserta</span>
        <h1 class="mt-3 font-display text-4xl font-black text-slate-950">Pengaturan Profil</h1>
        <p class="mt-2 text-slate-600">Perbarui informasi pribadi, preview avatar, dan password akun.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <aside class="space-y-6">
            <div class="bento-card p-6 text-center">
                <div id="avatar-preview" class="mx-auto flex h-28 w-28 items-center justify-center rounded-[2rem] bg-gradient-to-br from-lime-300 to-emerald-500 text-5xl font-black text-slate-950 shadow-soft">
                    <?= htmlspecialchars(mb_substr($user['nama'] ?? $userName, 0, 1)) ?>
                </div>
                <h2 class="mt-5 font-display text-2xl font-black text-slate-950"><?= htmlspecialchars($user['nama'] ?? $userName) ?></h2>
                <p class="mt-1 text-sm font-bold text-slate-500"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                <label class="btn-secondary mt-5 cursor-pointer">
                    <i class="fa-solid fa-camera"></i> Upload Avatar
                    <input id="avatar-input" type="file" accept="image/*" class="sr-only">
                </label>
                <p class="mt-3 text-xs font-bold text-slate-400">Preview avatar frontend. Tambahkan kolom avatar di database jika ingin menyimpan file.</p>
            </div>

            <div class="bento-card p-6">
                <span class="badge-soft">History Event</span>
                <div class="mt-4 space-y-3">
                    <?php if (empty($pendaftarans)): ?>
                        <p class="text-sm font-bold text-slate-500">Belum ada riwayat event.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($pendaftarans, 0, 4) as $item): ?>
                            <div class="rounded-3xl border border-slate-200 bg-white p-4">
                                <p class="font-black text-slate-950"><?= htmlspecialchars($item['nama_event']) ?></p>
                                <p class="mt-1 text-xs font-bold text-slate-500"><?= date('d M Y', strtotime($item['tanggal_event'])) ?> / <?= htmlspecialchars($item['status_checkin']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <form data-loading-submit action="<?= $baseUrl ?>/profile/update" method="POST" class="premium-card rounded-[2rem] p-6 sm:p-8">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <span class="badge-soft">Informasi Pribadi</span>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="nama">Nama</label>
                    <input class="input-modern" id="nama" name="nama" required value="<?= htmlspecialchars($user['nama'] ?? '') ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="email">Email</label>
                    <input class="input-modern" id="email" name="email" type="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="no_hp">No HP</label>
                    <input class="input-modern" id="no_hp" name="no_hp" required value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="instansi">Instansi</label>
                    <input class="input-modern" id="instansi" name="instansi" required value="<?= htmlspecialchars($user['instansi'] ?? '') ?>">
                </div>
                <div class="sm:col-span-2 mt-4 border-t border-slate-200 pt-6">
                    <span class="badge-soft">Update Password</span>
                    <p class="mt-2 text-sm font-bold text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="password">Password Baru</label>
                    <div class="relative">
                        <input class="input-modern pr-12" id="password" name="password" type="password" placeholder="Minimal 6 karakter">
                        <button type="button" data-password-toggle="#password" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="password_confirmation">Konfirmasi Password</label>
                    <div class="relative">
                        <input class="input-modern pr-12" id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password">
                        <button type="button" data-password-toggle="#password_confirmation" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end border-t border-slate-200 pt-6">
                <button type="submit" class="btn-accent"><i class="fa-solid fa-floppy-disk"></i> Simpan Profil</button>
            </div>
        </form>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/profile.js"></script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
