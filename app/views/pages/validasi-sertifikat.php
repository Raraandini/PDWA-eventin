<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="eventin-container py-24 min-h-[70vh]">
    <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-soft border border-slate-200 dark:border-slate-700 text-center">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-6"><?= $pageTitle ?></h1>
        <p class="text-slate-600 dark:text-slate-400 mb-8">
            Fitur Validasi Sertifikat sedang dalam pengembangan. Ke depannya, halaman ini dapat digunakan untuk memasukkan Kode Sertifikat (atau Token QR) guna mengecek keaslian sertifikat digital peserta.
        </p>
        
        <div class="inline-block p-4 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl border border-amber-200 dark:border-amber-800">
            <i class="fa-solid fa-person-digging text-2xl mb-2"></i>
            <p class="font-bold text-sm">Under Construction</p>
        </div>
    </div>
</div>
<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
