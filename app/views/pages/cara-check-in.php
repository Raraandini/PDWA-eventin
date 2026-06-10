<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="eventin-container py-24 min-h-[70vh]">
    <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-soft border border-slate-200 dark:border-slate-700">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-6"><?= $pageTitle ?></h1>
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400">
            <h3>Panduan Check-In di Lokasi Event</h3>
            <p>Untuk memasuki area event atau mengonfirmasi kehadiran Anda, ikuti langkah berikut:</p>
            <ul>
                <li>Siapkan tiket digital Anda yang bisa diakses melalui <strong>Email</strong> atau menu <strong>Tiket Saya</strong> di website Eventin.</li>
                <li>Pastikan <strong>QR Code</strong> pada tiket Anda terlihat dengan jelas di layar ponsel Anda (terangkan layar jika perlu).</li>
                <li>Tunjukkan QR Code tersebut kepada <strong>Petugas Scan</strong> atau panitia event di meja registrasi.</li>
                <li>Panitia akan memindai QR Code Anda menggunakan fitur <strong>QR Scanner</strong> dari sistem Eventin.</li>
                <li>Jika valid, status kehadiran Anda akan otomatis tercatat.</li>
            </ul>
        </div>
    </div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
