# Eventin

Eventin adalah aplikasi web Manajemen Event berbasis PHP dengan konsep MVC (Model-View-Controller) buatan sendiri, tanpa framework besar seperti Laravel, namun tetap terstruktur dan rapi. Aplikasi ini menggunakan Composer untuk mengelola beberapa dependensi eksternal.

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan sistem Anda sudah terinstal:
- **PHP** (minimal versi 7.4 atau lebih baru)
- **MySQL / MariaDB**
- **Composer**
- Web Server lokal seperti **Laragon** (disarankan karena sangat mudah digunakan di Windows), **XAMPP**, atau web server lainnya.

## Langkah-langkah Setup

Berikut adalah cara untuk melakukan setup dan menjalankan aplikasi ini di lokal menggunakan **Laragon** atau **XAMPP**:

### 1. Menyiapkan Folder Project
Pastikan folder project ini berada di dalam *document root* dari web server Anda.
- Jika menggunakan **Laragon**: letakkan folder ini di `C:\laragon\www\` atau direktori root Anda (misalnya `d:\laragon\www\Eventin`).
- Jika menggunakan **XAMPP**: letakkan di dalam folder `htdocs`.

### 2. Install Dependensi (Composer)
Aplikasi ini menggunakan beberapa library eksternal (seperti `phpmailer` dan `php-qrcode`). Anda perlu menginstalnya menggunakan Composer.

Buka Terminal atau Command Prompt, arahkan ke folder project ini, lalu jalankan perintah:

```bash
composer install
```

Perintah ini akan membuat folder `vendor/` dan mendownload semua library yang dibutuhkan sesuai dengan yang ada di `composer.json`.

### 3. Setup Database
Aplikasi ini membutuhkan database MySQL untuk menyimpan data.

1. Buka aplikasi manajemen database Anda (seperti **phpMyAdmin**, **HeidiSQL**, atau **DBeaver**).
2. Buat database baru bernama: `manajemen_event`
3. Import file `database.sql` yang sudah disediakan di folder root aplikasi ini ke dalam database `manajemen_event` tersebut. File ini berisi struktur tabel dan data awal yang dibutuhkan aplikasi.

### 4. Konfigurasi Database (Opsional)
Secara default, aplikasi ini diatur menggunakan konfigurasi database standar lokal. Jika Anda memiliki username atau password database yang berbeda, Anda bisa menyesuaikannya.

Buka file konfigurasi di: `app/config/Database.php`

```php
private static $host = 'localhost';
private static $db_name = 'manajemen_event';
private static $username = 'root';     // Sesuaikan dengan username DB Anda
private static $password = '';         // Sesuaikan dengan password DB Anda
```

### 5. Menjalankan Aplikasi

**A. Menggunakan Laragon (Disarankan):**
- Jalankan Laragon dan klik tombol **Start All** (untuk menjalankan Apache/Nginx dan MySQL).
- Laragon secara otomatis akan membuat *Virtual Host*.
- Jika document root diarahkan langsung ke folder utama project, buka browser dan akses: `http://eventin.test/public`
- (*Tips: Anda dapat mengonfigurasi root dari virtual host Laragon untuk langsung menunjuk ke folder `public/` agar URL menjadi lebih rapi yakni `http://eventin.test`*)

**B. Menggunakan XAMPP:**
- Jalankan XAMPP Control Panel, lalu *Start* modul **Apache** dan **MySQL**.
- Buka browser dan akses URL: `http://localhost/Eventin/public`

## Struktur Direktori

Aplikasi ini menggunakan struktur sebagai berikut:
- `/app` - Berisi logika utama aplikasi (Controllers, Models, Views, Helpers, Config).
- `/public` - Berisi file `index.php` yang menjadi pintu masuk (Front Controller) dari aplikasi serta aset publik seperti CSS, JS, dan Gambar.
- `/routes` - Berisi definisi rute aplikasi (URL mapping).
- `/vendor` - Folder hasil instalasi composer (library pihak ketiga).
- `composer.json` - Daftar library yang dibutuhkan.
- `database.sql` - File dump untuk database.

---
**Catatan:** Pastikan server lokal Anda mengaktifkan modul `mod_rewrite` untuk menangani URL dengan baik.
