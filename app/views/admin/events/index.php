<?php include dirname(dirname(__DIR__)) . '/layouts/header.php'; ?>

<?php if (!empty($success)): ?><div data-toast="<?= htmlspecialchars($success) ?>" data-toast-type="success"></div><?php endif; ?>
<?php if (!empty($error)): ?><div data-toast="<?= htmlspecialchars($error) ?>" data-toast-type="error"></div><?php endif; ?>

<section class="eventin-container py-8">
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="<?= $baseUrl ?>/admin" class="mb-3 inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-950"><i class="fa-solid fa-arrow-left"></i> Dashboard Admin</a>
            <span class="badge-soft">Admin Event</span>
            <h1 class="mt-3 font-display text-4xl font-black text-slate-950">Manajemen Event</h1>
            <p class="mt-2 text-slate-600">Tambah, edit, hapus, upload banner, dan atur status event.</p>
        </div>
        <a href="<?= $baseUrl ?>/admin/event/create" class="btn-accent"><i class="fa-solid fa-calendar-plus"></i> Tambah Event</a>
    </div>

    <div class="premium-card mb-6 rounded-[2rem] p-5">
        <div class="grid gap-3 md:grid-cols-[1fr_220px_120px]">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="admin-event-search" class="input-modern pl-11" placeholder="Cari judul event atau slug...">
            </div>
            <select id="admin-event-status" class="input-modern">
                <option value="all">Semua Status</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="finished">Finished</option>
            </select>
            <button type="button" onclick="window.EventinToast && window.EventinToast('Pagination client siap; data server saat ini menampilkan semua event.', 'info')" class="btn-secondary">Page 1</button>
        </div>
    </div>

    <?php if (empty($events)): ?>
        <div class="premium-card rounded-[2rem] p-10 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-lime-100 text-lime-800"><i class="fa-regular fa-calendar-minus"></i></div>
            <h3 class="mt-5 font-display text-2xl font-black text-slate-950">Belum Ada Event</h3>
            <p class="mt-2 text-sm text-slate-600">Buat event pertama untuk membuka pendaftaran peserta.</p>
            <a href="<?= $baseUrl ?>/admin/event/create" class="btn-accent mt-6">Buat Event</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Tanggal</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <?php
                        $percent = $event['kuota'] > 0 ? min(100, ($event['terdaftar'] / $event['kuota']) * 100) : 0;
                        $statusClass = $event['status'] === 'open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($event['status'] === 'closed' ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-slate-100 text-slate-600 border-slate-200');
                        ?>
                        <tr class="admin-event-row" data-title="<?= strtolower(htmlspecialchars($event['judul'] . ' ' . $event['slug'])) ?>" data-status="<?= htmlspecialchars($event['status']) ?>">
                            <td>
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-24 overflow-hidden rounded-2xl bg-slate-100">
                                        <?php if (!empty($event['banner']) && file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/public/uploads/' . $event['banner'])): ?>
                                            <img src="<?= $baseUrl ?>/uploads/<?= htmlspecialchars($event['banner']) ?>" alt="" class="h-full w-full object-cover">
                                        <?php else: ?>
                                            <div class="flex h-full w-full items-center justify-center bg-slate-950 text-lime-300"><i class="fa-solid fa-image"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="<?= $baseUrl ?>/event/<?= htmlspecialchars($event['slug']) ?>" class="font-black text-slate-950 hover:text-emerald-600"><?= htmlspecialchars($event['judul']) ?></a>
                                        <p class="mt-1 font-mono text-xs text-slate-400"><?= htmlspecialchars($event['slug']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="font-bold"><?= date('d M Y', strtotime($event['tanggal_event'])) ?></span><br><span class="text-xs text-slate-500"><?= date('H:i', strtotime($event['waktu_event'])) ?> WIB</span></td>
                            <td>
                                <p class="font-black text-slate-950"><?= $event['terdaftar'] ?> / <?= $event['kuota'] ?></p>
                                <div class="mt-2 h-2 w-28 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500 w-[<?= $percent ?>%]"></div></div>
                            </td>
                            <td><span class="rounded-full border px-3 py-1 text-xs font-black uppercase <?= $statusClass ?>"><?= htmlspecialchars($event['status']) ?></span></td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="<?= $baseUrl ?>/admin/event/edit/<?= $event['id'] ?>" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-indigo-500 hover:bg-indigo-50" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    <form action="<?= $baseUrl ?>/admin/event/delete/<?= $event['id'] ?>" method="POST" onsubmit="return confirm('Hapus event ini? Data pendaftaran terkait ikut terhapus.');">
                                        <button class="flex h-10 w-10 items-center justify-center rounded-2xl border border-red-200 bg-red-50 text-red-500 hover:bg-red-100" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="admin-event-empty" class="hidden premium-card mt-6 rounded-[2rem] p-8 text-center">
            <p class="font-display text-xl font-black text-slate-950">Tidak ada event yang cocok.</p>
        </div>
    <?php endif; ?>
</section>

<script src="<?= $baseUrl ?>/js/pages/admin-events-list.js"></script>

<?php include dirname(dirname(__DIR__)) . '/layouts/footer.php'; ?>
