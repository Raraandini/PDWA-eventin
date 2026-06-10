<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Event;
use App\Models\Pendaftaran;

class HomeController {
    private $eventModel;
    private $pendaftaranModel;

    public function __construct() {
        $this->eventModel = new Event();
        $this->pendaftaranModel = new Pendaftaran();
    }

    public function index() {
        $pageTitle = 'Beranda - Temukan Event Terbaik';
        
        $search = trim($_GET['q'] ?? '');
        $kategori = trim($_GET['kategori'] ?? '');
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 8;
        $offset = ($page - 1) * $limit;

        // Ambil semua event aktif/open dengan pagination
        $events = $this->eventModel->allActive($search, $kategori, $limit, $offset);
        $totalEvents = $this->eventModel->countActiveFiltered($search, $kategori);
        $totalPages = ceil($totalEvents / $limit);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/home.php';
    }

    public function detail($slug) {
        $event = $this->eventModel->findBySlug($slug);
        
        if (!$event) {
            http_response_code(404);
            $errorCode = 404;
            $errorMessage = "Event yang Anda cari tidak ditemukan.";
            include dirname(dirname(__DIR__)) . '/app/views/errors/error.php';
            exit();
        }
        
        $pageTitle = $event['judul'];
        $remainingKuota = $this->eventModel->getRemainingKuota($event['id']);
        
        // Cek apakah user sudah terdaftar di event ini
        $isRegistered = false;
        if (AuthHelper::isLoggedIn()) {
            $isRegistered = $this->pendaftaranModel->checkRegistered(
                AuthHelper::getUserId(),
                $event['id']
            );
        }
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/event-detail.php';
    }

    public function verify($token) {
        $registration = $this->pendaftaranModel->findByTokenQr($token);
        
        // Coba cari pakai kode_tiket jika token_qr tidak ditemukan
        if (!$registration) {
            $registration = $this->pendaftaranModel->findByKodeTiket($token);
        }
        
        $baseUrl = AuthHelper::getBaseUrl();
        $pageTitle = 'Verifikasi Keaslian Sertifikat';
        
        include dirname(dirname(__DIR__)) . '/app/views/verify.php';
    }

    // --- Halaman Informasi Statis ---

    public function panduanPendaftaran() {
        $pageTitle = 'Panduan Pendaftaran';
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/pages/panduan-pendaftaran.php';
    }

    public function caraCheckIn() {
        $pageTitle = 'Cara Check-In QR';
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/pages/cara-check-in.php';
    }

    public function syaratKetentuan() {
        $pageTitle = 'Syarat & Ketentuan';
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/pages/syarat-ketentuan.php';
    }

    public function faq() {
        $pageTitle = 'FAQ - Pertanyaan Umum';
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/pages/faq.php';
    }

    public function validasiSertifikat() {
        $pageTitle = 'Validasi Sertifikat';
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/pages/validasi-sertifikat.php';
    }
}
