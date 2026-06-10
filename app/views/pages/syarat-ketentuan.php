<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="eventin-container py-24 min-h-[70vh]">
    <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-soft border border-slate-200 dark:border-slate-700">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-6"><?= $pageTitle ?></h1>
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400">
            <h3>Persetujuan Penggunaan</h3>
            <p>Dengan mendaftar dan menggunakan layanan Eventin, Anda menyetujui syarat dan ketentuan berikut:</p>
            <ul>
                <li><strong>Keaslian Data:</strong> Anda wajib memberikan data yang valid dan benar saat registrasi (termasuk Nama Lengkap dan Email).</li>
                <li><strong>Penggunaan Tiket:</strong> Tiket (QR Code) hanya berlaku untuk satu orang dan tidak dapat dipindahtangankan tanpa persetujuan panitia event.</li>
                <li><strong>Pembatalan:</strong> Pembatalan kehadiran dapat dilakukan melalui Dashboard sebelum event dimulai, sesuai kebijakan masing-masing event.</li>
                <li><strong>Keamanan:</strong> Eventin menjaga kerahasiaan data Anda dan hanya menggunakannya untuk keperluan registrasi dan pengelolaan event.</li>
            </ul>
        </div>
    </div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
