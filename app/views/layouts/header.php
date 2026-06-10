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
        ? 'text-slate-950 bg-white shadow-sm border-slate-200 dark:bg-slate-700 dark:border-slate-600 dark:text-white'
        : 'text-slate-600 hover:text-slate-950 hover:bg-white/80 border-transparent dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50';
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
    <link rel="icon" href="<?= $baseUrl ?>/favicon.svg" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= $baseUrl ?>/js/tailwind-config.js"></script>
    <script src="<?= $baseUrl ?>/js/theme-toggle.js?v=<?= time() ?>"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= \App\Helpers\AuthHelper::generateCsrfToken() ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&family=Playfair+Display:wght@600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/main.css?v=<?= time() ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            <?= \App\Helpers\ThemeHelper::getActiveThemeVariables($_SESSION['theme_color'] ?? 'lime') ?>
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased <?= $isScannerPage ? 'scanner-shell' : '' ?>">
    <?php if (!$isScannerPage): ?>
        <div class="fixed inset-x-0 top-0 h-24 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-white/70 dark:border-slate-800/70 z-30 pointer-events-none"></div>
    <?php endif; ?>

    <header class="<?= $isScannerPage ? 'bg-slate-950/80 border-emerald-400/10' : 'bg-white/72 border-white/80 dark:bg-slate-900/80 dark:border-slate-800/80' ?> sticky top-0 z-50 border-b backdrop-blur-2xl">
        <div class="eventin-container">
            <div class="min-h-[72px] flex items-center justify-between gap-4">
                <a href="<?= $baseUrl ?>/" class="flex items-center gap-3 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="h-11 w-11 drop-shadow-md transition-transform duration-300 group-hover:scale-105 group-hover:rotate-[-2deg]">
                        <defs>
                            <linearGradient id="logo-grad-new" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="var(--theme-light-hex-300)" />
                                <stop offset="100%" stop-color="var(--theme-dark-hex-600)" />
                            </linearGradient>
                        </defs>
                        <!-- Geometric continuous lowercase 'e' -->
                        <path d="M 25 55 H 75 A 25 25 0 0 0 25 55 A 25 25 0 0 0 70 70" 
                              fill="none" stroke="url(#logo-grad-new)" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="leading-none">
                        <span class="block font-display text-[1.45rem] font-black tracking-tight <?= $isScannerPage ? 'text-white' : 'text-slate-950 dark:text-white' ?>">EVENTIN</span>
                        <span class="hidden sm:block mt-0.5 text-[0.65rem] font-black uppercase tracking-[0.2em] <?= $isScannerPage ? 'text-emerald-300' : 'text-slate-500 dark:text-slate-400' ?>">Tiket Seminar</span>
                    </span>
                </a>

                <?php if ($isLoggedIn): ?>
                <nav class="hidden lg:flex items-center gap-1 rounded-full border <?= $isScannerPage ? 'border-white/10 bg-white/5' : 'border-slate-200 bg-slate-100/70 dark:bg-slate-800/70 dark:border-slate-700' ?> p-1">
                    <a href="<?= $baseUrl ?>/" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/', $activePath) ?>">Beranda</a>
                    <?php if (!$isAdmin && !\App\Helpers\AuthHelper::isPetugas()): ?>
                        <a href="<?= $baseUrl ?>/dashboard" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/dashboard', $activePath) ?>">Dashboard</a>
                        <a href="<?= $baseUrl ?>/profile" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/profile', $activePath) ?>">Profil</a>
                    <?php elseif ($isAdmin): ?>
                        <a href="<?= $baseUrl ?>/admin" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin', $activePath) ?>">Dashboard Admin</a>
                        <a href="<?= $baseUrl ?>/admin/events" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/events', $activePath) ?>">Event</a>
                        <a href="<?= $baseUrl ?>/admin/peserta" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/peserta', $activePath) ?>">Peserta</a>
                        <a href="<?= $baseUrl ?>/admin/scan" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/scan', $activePath) ?>">Scanner</a>
                    <?php elseif (\App\Helpers\AuthHelper::isPetugas()): ?>
                        <a href="<?= $baseUrl ?>/admin/scan" class="px-4 py-2 rounded-full border text-sm font-bold transition <?= eventin_active('/admin/scan', $activePath) ?>">Scanner Kehadiran</a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>

                <div class="flex items-center gap-2">
                    <div class="hidden md:flex items-center gap-3 mr-1">
                        <?php if ($isLoggedIn): ?>
                            <div class="flex items-center gap-3 rounded-full border <?= $isScannerPage ? 'border-white/10 bg-white/5 text-white' : 'border-slate-200 bg-white/80 text-slate-900 dark:bg-slate-800/80 dark:border-slate-700 dark:text-white' ?> pl-2 pr-4 py-2">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-lime-300 to-emerald-500 text-slate-950 font-black uppercase overflow-hidden">
                                    <?php if (!empty($_SESSION['avatar'])): ?>
                                        <img src="<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Avatar" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <?= htmlspecialchars(mb_substr($userName, 0, 1)) ?>
                                    <?php endif; ?>
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

                    <button class="theme-toggle-btn inline-flex h-11 w-11 items-center justify-center rounded-2xl border <?= $isScannerPage ? 'border-white/10 bg-white/5 text-white' : 'border-slate-200 bg-white text-slate-950 dark:bg-slate-800 dark:border-slate-700 dark:text-white' ?> transition hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Toggle Dark Mode">
                        <i id="theme-icon-light" class="fa-solid fa-moon"></i>
                        <i id="theme-icon-dark" class="fa-solid fa-sun hidden text-amber-400"></i>
                    </button>

                    <button id="mobile-menu-btn" class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-2xl border <?= $isScannerPage ? 'border-white/10 bg-white/5 text-white' : 'border-slate-200 bg-white text-slate-950' ?>">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <div class="rounded-3xl border <?= $isScannerPage ? 'border-white/10 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white' ?> p-2 shadow-soft space-y-1">
                    <a href="<?= $baseUrl ?>/" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Beranda</a>
                    <?php if ($isLoggedIn && !$isAdmin && !\App\Helpers\AuthHelper::isPetugas()): ?>
                        <a href="<?= $baseUrl ?>/dashboard" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Dashboard Peserta</a>
                        <a href="<?= $baseUrl ?>/profile" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Profil</a>
                    <?php elseif ($isLoggedIn && $isAdmin): ?>
                        <a href="<?= $baseUrl ?>/admin" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Dashboard Admin</a>
                        <a href="<?= $baseUrl ?>/admin/events" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Manajemen Event</a>
                        <a href="<?= $baseUrl ?>/admin/peserta" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Tabel Peserta</a>
                        <a href="<?= $baseUrl ?>/admin/scan" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">QR Scanner</a>
                    <?php elseif ($isLoggedIn && \App\Helpers\AuthHelper::isPetugas()): ?>
                        <a href="<?= $baseUrl ?>/admin/scan" class="block rounded-2xl px-4 py-3 text-sm font-extrabold hover:bg-slate-100 hover:text-slate-950 dark:hover:bg-slate-700 dark:hover:text-white">Scanner Kehadiran</a>
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

    <main class="relative z-20 flex-grow">
        <div id="toast-root" class="fixed right-4 top-24 z-[80] space-y-3"></div>
