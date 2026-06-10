<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="eventin-container py-24 min-h-[70vh]">
    <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-soft border border-slate-200 dark:border-slate-700">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-6"><?= $pageTitle ?></h1>
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400">
            <h3>Langkah-langkah Mendaftar Event</h3>
            <ol>
                <li>Pilih event yang ingin Anda ikuti pada halaman <strong>Beranda</strong>.</li>
                <li>Klik tombol <strong>Daftar Event</strong>.</li>
                <li>Jika Anda belum login, silakan <strong>Login</strong> atau <strong>Daftar Akun Baru</strong> terlebih dahulu.</li>
                <li>Setelah berhasil mendaftar, tiket Anda akan dikirimkan melalui <strong>Email</strong>.</li>
                <li>Anda juga bisa melihat tiket Anda di menu <strong>Tiket Saya</strong> (Dashboard).</li>
            </ol>
            <p>Pastikan email yang Anda gunakan aktif untuk menerima tiket dan notifikasi event.</p>
        </div>
    </div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
