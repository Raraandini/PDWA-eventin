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
        
        // Ambil semua event aktif/open
        $events = $this->eventModel->allActive();
        
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
}
