<?php include dirname(dirname(__DIR__)) . '/layouts/header.php'; ?>

<section class="eventin-container py-8">
    <div class="mb-8">
        <a href="<?= $baseUrl ?>/admin/events" class="mb-3 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950"><i class="fa-solid fa-arrow-left"></i> Manajemen Event</a>
        <span class="badge-soft">Tambah Event</span>
        <h1 class="mt-3 font-display text-4xl font-black text-slate-950">Buat Event Baru</h1>
        <p class="mt-2 text-slate-600">Publikasikan event, kapasitas, banner, dan informasi lokasi.</p>
    </div>

    <?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <form data-loading-submit action="<?= $baseUrl ?>/admin/event/store" method="POST" enctype="multipart/form-data" class="premium-card rounded-[2rem] p-6 sm:p-8">
            <?= \App\Helpers\AuthHelper::getCsrfInput() ?>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700" for="judul">Judul Event</label>
                    <input class="input-modern" type="text" id="judul" name="judul" required placeholder="Contoh: Seminar Nasional Teknologi 2026">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700" for="deskripsi">Deskripsi</label>
                    <textarea class="input-modern min-h-40" id="deskripsi" name="deskripsi" rows="7" required placeholder="Agenda, speaker, fasilitas, dan ketentuan event..."></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-black text-slate-700" for="lokasi">Lokasi</label>
                    <input class="input-modern" type="text" id="lokasi" name="lokasi" required placeholder="Auditorium / venue / alamat lengkap">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="tanggal_event">Tanggal</label>
                    <input class="input-modern" type="date" id="tanggal_event" name="tanggal_event" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="waktu_event">Waktu</label>
                    <input class="input-modern" type="time" id="waktu_event" name="waktu_event" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="kuota">Kuota</label>
                    <input class="input-modern" type="number" id="kuota" name="kuota" min="1" required placeholder="150">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="kategori">Kategori</label>
                    <select class="input-modern" id="kategori" name="kategori">
                        <option value="Umum">Umum</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Bisnis">Bisnis</option>
                        <option value="Kesehatan">Kesehatan</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Desain">Desain</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Hiburan">Hiburan</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="batas_registrasi">Tutup Pendaftaran (Batas Waktu)</label>
                    <input class="input-modern" type="datetime-local" id="batas_registrasi" name="batas_registrasi">
                    <p class="mt-1 text-xs text-slate-500">Kosongkan jika pendaftaran ditutup hanya saat kuota habis.</p>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-black text-slate-700" for="banner">Banner Image</label>
                    <input class="input-modern" id="banner" name="banner" type="file" accept="image/*">
                </div>
            </div>
            <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                <a href="<?= $baseUrl ?>/admin/events" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-accent"><i class="fa-solid fa-floppy-disk"></i> Simpan Event</button>
            </div>
        </form>

        <aside class="space-y-6">
            <div class="bento-card overflow-hidden">
                <div id="banner-preview" class="h-56 bg-slate-950">
                    <?php
                    $previewEvent = ['judul' => 'Preview Banner', 'id' => 0];
                    $bannerSrc = \App\Helpers\BannerHelper::generateSvgImageUri($previewEvent);
                    ?>
                    <img id="banner-img" src="<?= $bannerSrc ?>" alt="Banner preview" class="h-full w-full object-cover">
                </div>
                <div class="p-5">
                    <p class="font-black text-slate-950">Upload banner modern</p>
                    <p class="mt-1 text-sm text-slate-600">JPG, PNG, WEBP maksimal 2MB untuk banner seminar.</p>
                </div>
            </div>
            <div class="bento-card p-6">
                <span class="badge-soft">Checklist</span>
                <ul class="mt-4 space-y-3 text-sm font-bold text-slate-600">
                    <li><i class="fa-solid fa-check mr-2 text-emerald-500"></i>Judul singkat dan jelas</li>
                    <li><i class="fa-solid fa-check mr-2 text-emerald-500"></i>Lokasi mudah ditemukan</li>
                    <li><i class="fa-solid fa-check mr-2 text-emerald-500"></i>Kuota sesuai kapasitas venue</li>
                </ul>
            </div>
        </aside>
    </div>
</section>

<script src="<?= $baseUrl ?>/js/pages/banner-preview.js"></script>

<?php include dirname(dirname(__DIR__)) . '/layouts/footer.php'; ?>
