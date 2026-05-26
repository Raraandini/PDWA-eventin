<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\QrHelper;
use App\Models\Event;
use App\Models\LogAbsensi;
use App\Models\Pendaftaran;

class PendaftaranController {
    private $eventModel;
    private $pendaftaranModel;
    private $logAbsensiModel;

    public function __construct() {
        $this->eventModel = new Event();
        $this->pendaftaranModel = new Pendaftaran();
        $this->logAbsensiModel = new LogAbsensi();
    }

    public function daftar($eventId) {
        AuthHelper::requireLogin();
        
        $userId = AuthHelper::getUserId();
        $event = $this->eventModel->findById($eventId);
        
        if (!$event) {
            $_SESSION['error'] = 'Event tidak ditemukan.';
            header("Location: " . AuthHelper::getBaseUrl() . "/");
            exit();
        }

        if ($event['status'] !== 'open') {
            $_SESSION['error'] = 'Pendaftaran untuk event ini sudah ditutup.';
            header("Location: " . AuthHelper::getBaseUrl() . "/event/" . $event['slug']);
            exit();
        }

        // Cek kuota sisa
        $remaining = $this->eventModel->getRemainingKuota($eventId);
        if ($remaining <= 0) {
            $_SESSION['error'] = 'Maaf, kuota untuk event ini sudah penuh.';
            header("Location: " . AuthHelper::getBaseUrl() . "/event/" . $event['slug']);
            exit();
        }

        // Cek pendaftaran ganda
        if ($this->pendaftaranModel->checkRegistered($userId, $eventId)) {
            $_SESSION['error'] = 'Anda sudah terdaftar di event ini.';
            header("Location: " . AuthHelper::getBaseUrl() . "/event/" . $event['slug']);
            exit();
        }

        // Generate data pendaftaran unik
        $tahun = date('Y');
        // Kode tiket format: EVT-2026-X7Y8Z
        $randomStr = strtoupper(bin2hex(random_bytes(3))); // 6 karakter acak
        $kodeTiket = "EVT-{$tahun}-{$randomStr}";
        
        // Token QR acak unik
        $tokenQr = bin2hex(random_bytes(16));

        // Generate QR Code Offline (SVG File)
        // Kita simpan file QR code dengan nama kode_tiket.svg
        $qrPath = QrHelper::generateFile($tokenQr, $kodeTiket);

        // Simpan pendaftaran ke database
        $success = $this->pendaftaranModel->create($userId, $eventId, $kodeTiket, $tokenQr);

        if ($success) {
            $_SESSION['success'] = 'Registrasi event berhasil! Ini adalah tiket digital Anda.';
            header("Location: " . AuthHelper::getBaseUrl() . "/ticket/" . $kodeTiket);
        } else {
            $_SESSION['error'] = 'Gagal melakukan pendaftaran. Silakan coba beberapa saat lagi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/event/" . $event['slug']);
        }
        exit();
    }

    public function ticket($kodeTiket) {
        AuthHelper::requireLogin();
        
        $ticket = $this->pendaftaranModel->findByKodeTiket($kodeTiket);
        
        if (!$ticket) {
            http_response_code(404);
            $errorCode = 404;
            $errorMessage = "Tiket digital tidak ditemukan.";
            include dirname(dirname(__DIR__)) . '/app/views/errors/error.php';
            exit();
        }

        // Cek otorisasi akses tiket (hanya pemilik tiket atau admin yang boleh melihat)
        if (!AuthHelper::isAdmin() && $ticket['user_id'] != AuthHelper::getUserId()) {
            http_response_code(403);
            $errorCode = 403;
            $errorMessage = "Akses Ditolak: Anda tidak berhak melihat tiket ini.";
            include dirname(dirname(__DIR__)) . '/app/views/errors/error.php';
            exit();
        }

        $pageTitle = "Tiket Digital - " . $ticket['kode_tiket'];
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/dashboard/ticket.php';
    }

    public function sertifikat($kodeTiket) {
        AuthHelper::requireLogin();
        
        $ticket = $this->pendaftaranModel->findByKodeTiket($kodeTiket);
        
        if (!$ticket) {
            http_response_code(404);
            $errorCode = 404;
            $errorMessage = "Sertifikat tidak ditemukan.";
            include dirname(dirname(__DIR__)) . '/app/views/errors/error.php';
            exit();
        }

        // Cek otorisasi akses sertifikat (hanya pemilik atau admin)
        if (!AuthHelper::isAdmin() && $ticket['user_id'] != AuthHelper::getUserId()) {
            http_response_code(403);
            $errorCode = 403;
            $errorMessage = "Akses Ditolak: Anda tidak berhak melihat sertifikat ini.";
            include dirname(dirname(__DIR__)) . '/app/views/errors/error.php';
            exit();
        }

        // Pastikan peserta sudah hadir
        if ($ticket['status_checkin'] !== 'hadir') {
            $_SESSION['error'] = 'Sertifikat belum tersedia. Anda harus melakukan check-in kehadiran di event terlebih dahulu.';
            header("Location: " . AuthHelper::getBaseUrl() . "/dashboard");
            exit();
        }

        $pageTitle = "E-Certificate - " . $ticket['nama_peserta'];
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/dashboard/certificate.php';
    }

    public function processScan() {
        // Output as JSON
        header('Content-Type: application/json');

        // Pastikan login sebagai admin
        if (!AuthHelper::isAdmin()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
            exit();
        }

        // Baca input JSON
        $inputData = json_decode(file_get_contents('php://input'), true);
        $tokenQr = trim($inputData['token_qr'] ?? '');
        $adminId = AuthHelper::getUserId();

        if (empty($tokenQr)) {
            echo json_encode([
                'status' => 'invalid',
                'message' => 'Token QR kosong atau tidak terbaca.'
            ]);
            exit();
        }

        // Temukan pendaftaran berdasarkan token QR
        $registration = $this->pendaftaranModel->findByTokenQr($tokenQr);

        if (!$registration) {
            // Catat log absensi scan invalid (pendaftaran_id = 0 karena tidak valid)
            // Di database schema pendaftaran_id is INT NOT NULL. Jadi untuk scan invalid, kita tidak bisa menyimpan foreign key pendaftaran_id = 0.
            // Wait, untuk scan invalid, log absensi tidak bisa kita simpan di tabel jika pendaftaran_id is NOT NULL. 
            // Let's check: can we just return 'invalid' without inserting to DB or can we adjust it?
            // Yes! Jika token QR tidak terdaftar di database, kita tidak memiliki pendaftaran_id, sehingga kita return JSON invalid langsung. Ini aman dan logis.
            echo json_encode([
                'status' => 'invalid',
                'message' => 'Tiket QR TIDAK VALID atau palsu!'
            ]);
            exit();
        }

        $pendaftaranId = $registration['id'];

        // Cek apakah sudah pernah check-in
        if ($registration['status_checkin'] === 'hadir') {
            // Catat log absensi sebagai duplikat
            $this->logAbsensiModel->create($pendaftaranId, $adminId, 'duplikat', "Scan ganda terdeteksi. Peserta sudah hadir.");
            
            echo json_encode([
                'status' => 'duplikat',
                'message' => 'Tiket QR sudah pernah digunakan!',
                'data' => [
                    'nama' => $registration['nama_peserta'],
                    'event' => $registration['nama_event'],
                    'waktu_checkin' => date('d-m-Y H:i:s', strtotime($registration['waktu_checkin'] ?? ''))
                ]
            ]);
            exit();
        }

        // Check-in sukses! Update status dan catat log absensi
        $updateSuccess = $this->pendaftaranModel->checkIn($pendaftaranId, 'hadir');

        if ($updateSuccess) {
            // Catat log absensi berhasil
            $this->logAbsensiModel->create($pendaftaranId, $adminId, 'berhasil', "Check-in sukses via scanner.");
            
            echo json_encode([
                'status' => 'berhasil',
                'message' => 'Check-in berhasil! Selamat datang.',
                'data' => [
                    'nama' => $registration['nama_peserta'],
                    'email' => $registration['email_peserta'],
                    'instansi' => $registration['instansi_peserta'],
                    'event' => $registration['nama_event'],
                    'waktu' => date('d-m-Y H:i:s')
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperbarui status check-in di server.'
            ]);
        }
        exit();
    }
}
