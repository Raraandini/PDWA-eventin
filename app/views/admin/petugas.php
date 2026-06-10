<?php include dirname(__DIR__) . '/layouts/header.php'; ?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>
<?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

<section class="eventin-container py-8">
    <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="font-display text-3xl font-black text-slate-950">Manajemen Petugas Scanner</h1>
            <p class="mt-2 text-slate-600">Kelola akun petugas yang bertugas melakukan scan QR code kehadiran di lapangan.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah-petugas').classList.remove('hidden')" class="btn-accent shrink-0">
            <i class="fa-solid fa-plus"></i> Tambah Petugas
        </button>
    </div>

    <div class="premium-card overflow-hidden rounded-[2rem]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-black">Nama</th>
                        <th class="px-6 py-4 font-black">Email</th>
                        <th class="px-6 py-4 font-black">Terdaftar Sejak</th>
                        <th class="px-6 py-4 text-right font-black">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($petugas)): ?>
                        <tr>
                            <td colspan="4" class="p-10 text-center">
                                <i class="fa-solid fa-user-shield text-3xl text-slate-300"></i>
                                <h3 class="mt-4 font-display text-xl font-black text-slate-950">Belum Ada Petugas</h3>
                                <p class="mt-2 text-slate-500">Silakan tambahkan petugas baru untuk membantu scan QR peserta.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($petugas as $p): ?>
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-6 py-4 font-bold text-slate-900"><?= htmlspecialchars($p['nama']) ?></td>
                                <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($p['email']) ?></td>
                                <td class="px-6 py-4 text-slate-500"><?= date('d M Y', strtotime($p['dibuat_pada'])) ?></td>
                                <td class="px-6 py-4 text-right">
                                    <form action="<?= $baseUrl ?>/admin/petugas/delete/<?= $p['id'] ?>" method="POST" class="inline-block delete-petugas-form">
                                        <?= \App\Helpers\AuthHelper::getCsrfInput() ?>
                                        <button type="button" class="btn-delete-trigger inline-flex h-8 w-8 items-center justify-center rounded-xl bg-red-50 text-red-600 transition-colors hover:bg-red-100" title="Hapus Petugas">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah Petugas -->
<div id="modal-tambah-petugas" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modal-tambah-petugas').classList.add('hidden')"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md scale-100 opacity-100 transition-all my-8">
            <div class="rounded-[2rem] bg-white p-8 shadow-lift">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="font-display text-2xl font-black text-slate-950">Tambah Petugas</h3>
                    <button onclick="document.getElementById('modal-tambah-petugas').classList.add('hidden')" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form action="<?= $baseUrl ?>/admin/petugas/store" method="POST" class="space-y-4">
                    <?= \App\Helpers\AuthHelper::getCsrfInput() ?>
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-black text-slate-700">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="input-modern" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-black text-slate-700">Email Petugas</label>
                        <input type="email" id="email" name="email" class="input-modern" required placeholder="Contoh: budi@eventin.com">
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-black text-slate-700">Password Sementara</label>
                        <input type="password" id="password" name="password" class="input-modern" required placeholder="Minimal 6 karakter">
                        <p class="mt-2 text-xs font-bold text-slate-500">Berikan password ini kepada petugas untuk login.</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="btn-accent w-full justify-center">Simpan Petugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-petugas-form').forEach(function(form) {
        const btn = form.querySelector('.btn-delete-trigger');
        if (btn) {
            btn.addEventListener('click', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus Petugas?',
                        text: "Akses petugas ini akan dicabut secara permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-lift border border-slate-100',
                            title: 'font-display font-black text-slate-900',
                            confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                            cancelButton: 'rounded-xl font-bold px-6 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menghapus petugas ini?')) {
                        form.submit();
                    }
                }
            });
        }
    });
});
</script>

<?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
