<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Certificate <?= htmlspecialchars($ticket['nama_peserta']) ?> - Eventin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700;800;900&family=Playfair+Display:wght@600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/pages/certificate.css">
</head>
<body class="min-h-screen p-4 sm:p-8">
    <div class="no-print mx-auto mb-6 flex w-full max-w-[1120px] items-center justify-between gap-4">
        <a href="<?= $baseUrl ?>/dashboard" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-600 shadow-sm hover:text-slate-950"><i class="fa-solid fa-arrow-left mr-2"></i>Dashboard</a>
        <button onclick="window.print()" class="rounded-full bg-slate-950 px-6 py-3 text-sm font-black text-white shadow-lg"><i class="fa-solid fa-file-pdf mr-2"></i>Download PDF / Print</button>
    </div>

    <main class="mx-auto certificate overflow-hidden rounded-[1.5rem] shadow-2xl">
        <div class="absolute inset-0 flex items-center justify-center opacity-[.035]">
            <i class="fa-solid fa-award text-[440px] text-blue-950"></i>
        </div>

        <div class="relative z-10 flex h-full flex-col justify-between p-[7%] text-center">
            <header>
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border-2 border-amber-600 bg-white text-amber-600">
                    <i class="fa-solid fa-medal text-3xl"></i>
                </div>
                <p class="text-xs font-black uppercase tracking-[.32em] text-amber-700">EVENTIN OFFICIAL CERTIFICATE</p>
                <h1 class="serif mt-2 text-4xl font-black uppercase tracking-wide text-blue-950 sm:text-6xl">Certificate of Attendance</h1>
                <p class="mt-2 font-mono text-[10px] font-bold uppercase tracking-[.22em] text-slate-400">No: CERT/<?= htmlspecialchars($ticket['kode_tiket']) ?>/<?= date('Y', strtotime($ticket['waktu_checkin'])) ?></p>
            </header>

            <section>
                <p class="text-sm font-black uppercase tracking-[.22em] text-slate-500">Diberikan kepada</p>
                <h2 class="serif mx-auto mt-4 inline-block border-b-2 border-dashed border-amber-500/40 px-10 pb-3 text-4xl font-black text-blue-950 sm:text-6xl">
                    <?= htmlspecialchars($ticket['nama_peserta']) ?>
                </h2>
                <p class="mx-auto mt-6 max-w-3xl text-base font-semibold leading-8 text-slate-600">
                    Atas partisipasi dan kehadirannya sebagai peserta pada konferensi / event resmi:
                </p>
                <h3 class="serif mt-3 text-2xl font-black uppercase tracking-wide text-amber-700 sm:text-3xl">
                    "<?= htmlspecialchars($ticket['nama_event']) ?>"
                </h3>
            </section>

            <footer class="grid grid-cols-3 items-end gap-6 text-center">
                <div class="border-t border-slate-300 pt-3">
                    <p class="signature text-4xl text-blue-950">Eventin Team</p>
                    <p class="text-sm font-black text-slate-800">Administrator</p>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Panitia Penyelenggara</p>
                </div>
                <div class="mx-auto flex flex-col items-center">
                    <div class="h-20 w-20 rounded-xl border border-slate-200 bg-white p-2 shadow-sm">
                        <img src="<?= $baseUrl ?>/qrcode/<?= htmlspecialchars($ticket['kode_tiket']) ?>.svg" alt="QR Validasi" class="h-full w-full object-contain">
                    </div>
                    <p class="mt-2 text-[9px] font-black uppercase tracking-[.18em] text-slate-400">Validasi QR</p>
                </div>
                <div class="border-t border-slate-300 pt-3">
                    <p class="flex h-12 items-center justify-center text-sm font-black text-slate-700"><?= date('d F Y', strtotime($ticket['tanggal_event'])) ?></p>
                    <p class="text-sm font-black text-slate-800"><?= htmlspecialchars($ticket['lokasi']) ?></p>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Tanggal & Tempat</p>
                </div>
            </footer>
        </div>
    </main>
</body>
</html>
