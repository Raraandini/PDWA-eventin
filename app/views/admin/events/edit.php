<?php include dirname(dirname(__DIR__)) . '/layouts/header.php'; ?>

<section class="eventin-container py-8">
    <div class="mb-8">
        <a href="<?= $baseUrl ?>/admin/events" class="mb-3 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950 dark:text-slate-400 dark:hover:text-white"><i class="fa-solid fa-arrow-left"></i> Manajemen Event</a>
        <span class="badge-soft">Edit Event</span>
        <h1 class="mt-3 font-display text-4xl font-black text-slate-950 dark:text-white">Sunting Event</h1>
        <p class="mt-2 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($event['judul']) ?></p>
    </div>

    <?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <form data-loading-submit action="<?= $baseUrl ?>/admin/event/update/<?= $event['id'] ?>" method="POST" enctype="multipart/form-data" class="premium-card rounded-[2rem] p-6 sm:p-8">
            <?= \App\Helpers\AuthHelper::getCsrfInput() ?>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="judul">Judul Event</label>
                    <input class="input-modern" type="text" id="judul" name="judul" required value="<?= htmlspecialchars($event['judul']) ?>">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="deskripsi">Deskripsi</label>
                    <textarea class="input-modern min-h-40" id="deskripsi" name="deskripsi" rows="7" required><?= htmlspecialchars($event['deskripsi']) ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="lokasi">Lokasi</label>
                    <input class="input-modern" type="text" id="lokasi" name="lokasi" required value="<?= htmlspecialchars($event['lokasi']) ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="tanggal_event">Tanggal</label>
                    <input class="input-modern" type="date" id="tanggal_event" name="tanggal_event" required value="<?= htmlspecialchars($event['tanggal_event']) ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="waktu_event">Waktu</label>
                    <input class="input-modern" type="time" id="waktu_event" name="waktu_event" required value="<?= date('H:i', strtotime($event['waktu_event'])) ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="kuota">Kuota</label>
                    <input class="input-modern" type="number" id="kuota" name="kuota" min="1" required value="<?= htmlspecialchars($event['kuota']) ?>">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="status">Status</label>
                    <select class="input-modern" id="status" name="status" required>
                        <option value="open" <?= $event['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="closed" <?= $event['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                        <option value="finished" <?= $event['status'] === 'finished' ? 'selected' : '' ?>>Finished</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="kategori">Kategori</label>
                    <select class="input-modern" id="kategori" name="kategori">
                        <?php $cats = ['Umum', 'Teknologi', 'Bisnis', 'Kesehatan', 'Pendidikan', 'Desain', 'Olahraga', 'Hiburan']; ?>
                        <?php foreach($cats as $c): ?>
                            <option value="<?= $c ?>" <?= ($event['kategori'] ?? 'Umum') === $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="batas_registrasi">Tutup Pendaftaran (Batas Waktu)</label>
                    <input class="input-modern" type="datetime-local" id="batas_registrasi" name="batas_registrasi" value="<?= !empty($event['batas_registrasi']) ? date('Y-m-d\TH:i', strtotime($event['batas_registrasi'])) : '' ?>">
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Kosongkan jika pendaftaran ditutup hanya saat kuota habis.</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700 dark:text-slate-300" for="banner">Ganti Banner</label>
                    <input class="input-modern" id="banner" name="banner" type="file" accept="image/*">
                </div>
            </div>
            <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 dark:border-slate-700 pt-6 sm:flex-row sm:justify-end">
                <a href="<?= $baseUrl ?>/admin/events" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-accent"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
            </div>
        </form>

        <aside class="space-y-6">
            <div class="bento-card overflow-hidden">
                <div id="banner-preview" class="h-56 bg-slate-950">
                    <?php 
                    $hasBanner = !empty($event['banner']) && file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/' . $event['banner']);
                    $bannerSrc = $hasBanner ? $baseUrl . '/uploads/' . htmlspecialchars($event['banner']) : \App\Helpers\BannerHelper::generateSvgImageUri($event);
                    ?>
                    <img id="banner-img" src="<?= $bannerSrc ?>" alt="Banner preview" class="h-full w-full object-cover">
                </div>
                <div class="p-5">
                    <p class="font-black text-slate-950 dark:text-white">Banner Saat Ini</p>
                    <p class="mt-1 truncate text-sm text-slate-600 dark:text-slate-400"><?= htmlspecialchars($event['banner'] ?: 'Tidak ada banner') ?></p>
                </div>
            </div>
            <div class="bento-card p-6">
                <span class="badge-soft">Status Event</span>
                <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-400">Open untuk menerima pendaftaran, closed untuk menutup registrasi, finished untuk event selesai.</p>
            </div>
        </aside>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/banner-preview.js"></script>

<?php include dirname(dirname(__DIR__)) . '/layouts/footer.php'; ?>
