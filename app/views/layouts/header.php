<?php
$isLoggedIn = \App\Helpers\AuthHelper::isLoggedIn();
$isAdmin = \App\Helpers\AuthHelper::isAdmin();
$userName = \App\Helpers\AuthHelper::getUserName();
$baseUrl = \App\Helpers\AuthHelper::getBaseUrl();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$relativePath = $currentPath;
if ($baseUrl !== '' && str_starts_with($relativePath, $baseUrl)) {
    $relativePath = substr($relativePath, strlen($baseUrl));
}
$relativePath = '/' . ltrim($relativePath ?: '/', '/');
$isScannerPage = str_contains($currentPath, '/admin/scan');

function eventin_active($path, $activePath) {
    return $path === $activePath
        ? 'text-slate-950 bg-white shadow-sm border-slate-200'
        : 'text-slate-600 hover:text-slate-950 hover:bg-white/80 border-transparent';
}

$activePath = match (true) {
    str_starts_with($relativePath, '/admin/scan') => '/admin/scan',
    str_starts_with($relativePath, '/admin/events') || str_starts_with($relativePath, '/admin/event') => '/admin/events',
    str_starts_with($relativePath, '/admin/peserta') => '/admin/peserta',
    $relativePath === '/admin' => '/admin',
    $relativePath === '/dashboard' => '/dashboard',
    $relativePath === '/profile' => '/profile',
    $relativePath === '/' || $relativePath === '/events' => '/',
    default => '',
};
?>
<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'EVENTIN - Tiket Seminar Online') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= $baseUrl ?>/js/tailwind-config.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&family=Playfair+Display:wght@600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/main.css">
</head>
<body class="min-h-screen flex flex-col antialiased <?= $isScannerPage ? 'scanner-shell' : '' ?>">
    <?php if (!$isScannerPage): ?>
        <div class="fixed inset-x-0 top-0 h-24 bg-white/70 backdrop-blur-xl border-b border-white/70 z-30 pointer-events-none"></div>
    <?php endif; ?>

    <header class="<?= $isScannerPage ? 'bg-slate-950/80 border-emerald-400/10' : 'bg-white/72 border-white/80' ?> sticky top-0 z-50 border-b backdrop-blur-2xl">
        <div class="eventin-container">
            <div class="min-h-[72px] flex items-center justify-between gap-4">
                <a href="<?= $baseUrl ?>/" class="flex items-center gap-3 group">
                    <span class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-lime-300 shadow-soft overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-br from-lime-300/30 via-transparent to-emerald-400/30"></span>
                        <i class="fa-solid fa-bolt-lightning relative"></i>
                    </span>
                    <span class="leading-none">
                        <span class="block font-display text-xl font-black tracking-tight <?= $isScannerPage ? 'text-white' : 'text-slate-950' ?>">EVENTIN</span>
                        <span class="hidden sm:block text-[10px] font-black uppercase tracking-[0.18em] <?= $isScannerPage ? 'text-emerald-300' : 'text-slate-500' ?>">Tiket Seminar</span>
                    </span>
                </a>

                <nav class="hidden lg:flex items-center gap-1 rounded-full border <?= $isScannerPage ? 'border-white/10 bg-white/5' : 'border-slate-200 bg-slate-100/70' ?> p-1">
                    <a href="<?= $baseUrl ?>/" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/', $activePath) ?>">Beranda</a>
                    <?php if ($isLoggedIn && !$isAdmin): ?>
                        <a href="<?= $baseUrl ?>/dashboard" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/dashboard', $activePath) ?>">Dashboard</a>
                        <a href="<?= $baseUrl ?>/profile" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/profile', $activePath) ?>">Profil</a>
                    <?php endif; ?>
                    <?php if ($isLoggedIn && $isAdmin): ?>
                        <a href="<?= $baseUrl ?>/admin" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin', $activePath) ?>">Dashboard Admin</a>
                        <a href="<?= $baseUrl ?>/admin/events" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/events', $activePath) ?>">Event</a>
                        <a href="<?= $baseUrl ?>/admin/peserta" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/peserta', $activePath) ?>">Peserta</a>
                        <a href="<?= $baseUrl ?>/admin/scan" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/scan', $activePath) ?>">Scanner</a>
                    <?php endif; ?>
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    <?php if ($isLoggedIn): ?>
                        <div class="flex items-center gap-3 rounded-full border <?= $isScannerPage ? 'border-white/10 bg-white/5 text-white' : 'border-slate-200 bg-white/80 text-slate-900' ?> pl-2 pr-4 py-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-lime-300 to-emerald-500 text-slate-950 font-black uppercase">
                                <?= htmlspecialchars(mb_substr($userName, 0, 1)) ?>
                            </span>
                            <span class="leading-tight">
                                <span class="block text-sm font-extrabold"><?= htmlspecialchars($userName) ?></span>
                                <span class="block text-[10px] uppercase font-black <?= $isScannerPage ? 'text-emerald-300' : 'text-slate-500' ?>"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span>
                            </span>
                        </div>
                        <a href="<?= $baseUrl ?>/logout" class="btn-secondary <?= $isScannerPage ? '!bg-white/5 !text-white !border-white/10 hover:!bg-white/10' : '' ?>">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>/login" class="btn-secondary">Masuk</a>
                        <a href="<?= $baseUrl ?>/register" class="btn-accent">Mulai Gratis</a>
                    <?php endif; ?>
                </div>

                <button id="mobile-menu-btn" class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-2xl border <?= $isScannerPage ? 'border-white/10 bg-white/5 text-white' : 'border-slate-200 bg-white text-slate-950' ?>">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <div class="rounded-3xl border <?= $isScannerPage ? 'border-white/10 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-900' ?> p-2 shadow-soft space-y-1">
                    <a href="<?= $baseUrl ?>/" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">Beranda</a>
                    <?php if ($isLoggedIn && !$isAdmin): ?>
                        <a href="<?= $baseUrl ?>/dashboard" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">Dashboard Peserta</a>
                        <a href="<?= $baseUrl ?>/profile" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">Profil</a>
                    <?php elseif ($isLoggedIn && $isAdmin): ?>
                        <a href="<?= $baseUrl ?>/admin" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">Dashboard Admin</a>
                        <a href="<?= $baseUrl ?>/admin/events" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">Manajemen Event</a>
                        <a href="<?= $baseUrl ?>/admin/peserta" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">Tabel Peserta</a>
                        <a href="<?= $baseUrl ?>/admin/scan" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950">QR Scanner</a>
                    <?php endif; ?>
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <?php if ($isLoggedIn): ?>
                            <a href="<?= $baseUrl ?>/logout" class="btn-secondary col-span-2">Keluar</a>
                        <?php else: ?>
                            <a href="<?= $baseUrl ?>/login" class="btn-secondary">Masuk</a>
                            <a href="<?= $baseUrl ?>/register" class="btn-accent">Daftar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="relative z-10 flex-grow">
        <div id="toast-root" class="fixed right-4 top-24 z-[80] space-y-3"></div>
