<?php include 'layouts/header.php'; ?>

<main class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-10 rounded-[2rem] shadow-soft text-center border border-slate-100">
        <?php if ($registration): ?>
            <?php if ($registration['status_checkin'] === 'hadir'): ?>
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-emerald-100 text-emerald-500 mb-6 relative">
                    <div class="absolute inset-0 rounded-full animate-ping bg-emerald-400 opacity-20"></div>
                    <i class="fa-solid fa-certificate text-5xl"></i>
                </div>
                <h2 class="text-3xl font-display font-black text-slate-900">Valid & Asli</h2>
                <p class="mt-2 text-sm text-slate-600">Sertifikat ini resmi diterbitkan oleh sistem EVENTIN.</p>
                
                <div class="mt-8 bg-slate-50 rounded-2xl p-6 text-left border border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Diberikan Kepada</p>
                    <p class="font-black text-slate-800 text-lg mb-4"><?= htmlspecialchars($registration['nama_peserta']) ?></p>
                    
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Nama Event</p>
                    <p class="font-bold text-slate-700 mb-4"><?= htmlspecialchars($registration['nama_event']) ?></p>
                    
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Status Kehadiran</p>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black">
                        <i class="fa-solid fa-check-circle"></i> Terkonfirmasi Hadir
                    </div>
                    <p class="mt-2 text-xs font-medium text-slate-500"><i class="fa-regular fa-clock mr-1"></i><?= date('d M Y, H:i', strtotime($registration['waktu_checkin'])) ?> WIB</p>
                </div>
            <?php else: ?>
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-blue-100 text-blue-500 mb-6">
                    <i class="fa-solid fa-certificate text-5xl"></i>
                </div>
                <h2 class="text-3xl font-display font-black text-slate-900">Sertifikat Valid</h2>
                <p class="mt-2 text-sm text-slate-600">Sertifikat ini terdaftar di sistem, namun peserta belum memindai / check-in kehadiran di venue.</p>
                
                <div class="mt-8 bg-slate-50 rounded-2xl p-6 text-left border border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Peserta</p>
                    <p class="font-black text-slate-800 text-lg mb-4"><?= htmlspecialchars($registration['nama_peserta']) ?></p>
                    
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Event</p>
                    <p class="font-bold text-slate-700 mb-4"><?= htmlspecialchars($registration['nama_event']) ?></p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 text-red-500 mb-6 relative">
                <i class="fa-solid fa-triangle-exclamation text-5xl"></i>
            </div>
            <h2 class="text-3xl font-display font-black text-slate-900">Tidak Valid</h2>
            <p class="mt-4 text-sm text-slate-600 bg-red-50 text-red-700 p-4 rounded-xl border border-red-100">Maaf, Token / QR Code ini tidak ditemukan di dalam sistem kami. Dokumen kemungkinan palsu atau sudah tidak berlaku.</p>
        <?php endif; ?>
        
        <div class="mt-8 pt-6 border-t border-slate-100">
            <a href="<?= $baseUrl ?>" class="btn-secondary w-full justify-center text-sm"><i class="fa-solid fa-house mr-2"></i>Kembali ke Beranda EVENTIN</a>
        </div>
    </div>
</main>

<?php include 'layouts/footer.php'; ?>
