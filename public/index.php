<?php

// Front Controller

// Muat Composer autoloader jika ada
$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

// Autoloader sederhana untuk PSR-4 namespace App
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $parts = explode('\\', $relative_class);
    
    // Ubah nama folder tingkat pertama (Config, Helpers, Controllers, Models) menjadi lowercase
    if (count($parts) > 1) {
        $parts[0] = strtolower($parts[0]);
    }
    
    $file = $base_dir . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Jalankan session security helper secara default
App\Helpers\AuthHelper::startSession();

// Muat definisi routes
require_once dirname(__DIR__) . '/routes/web.php';

// Dispatch request
App\Helpers\Router::dispatch();
