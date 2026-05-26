<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Event;
use App\Models\LogAbsensi;
use App\Models\Pendaftaran;
use App\Models\User;

class AdminController {
    private $eventModel;
    private $pendaftaranModel;
    private $logAbsensiModel;
    private $userModel;

    public function __construct() {
        // Proteksi route admin
        AuthHelper::requireAdmin();
        
        $this->eventModel = new Event();
        $this->pendaftaranModel = new Pendaftaran();
        $this->logAbsensiModel = new LogAbsensi();
        $this->userModel = new User();
    }

    public function index() {
        $pageTitle = 'Dashboard Admin';
        
        // Statistik
        $totalEvents = $this->eventModel->countAll();
        $totalPeserta = $this->userModel->countAllPeserta();
        $totalPendaftaran = $this->pendaftaranModel->countAll();
        $totalHadir = $this->pendaftaranModel->countHadir();
        $activeEvents = $this->eventModel->countActive();
        
        // Persentase kehadiran
        $attendancePercent = ($totalPendaftaran > 0) ? round(($totalHadir / $totalPendaftaran) * 100, 1) : 0;
        
        // Log absensi scan terbaru
        $recentLogs = $this->logAbsensiModel->getLatestLogs(5);
        
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/admin/dashboard.php';
    }

    public function events() {
        $pageTitle = 'Manajemen Event';
        $events = $this->eventModel->all();
        
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/admin/events/index.php';
    }

    public function createEvent() {
        $pageTitle = 'Tambah Event Baru';
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/admin/events/create.php';
    }

    public function storeEvent() {
        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $tanggal_event = $_POST['tanggal_event'] ?? '';
        $waktu_event = $_POST['waktu_event'] ?? '';
        $kuota = (int)($_POST['kuota'] ?? 0);
        $adminId = AuthHelper::getUserId();

        if (empty($judul) || empty($deskripsi) || empty($lokasi) || empty($tanggal_event) || empty($waktu_event) || $kuota <= 0) {
            $_SESSION['error'] = 'Semua field wajib diisi dengan benar.';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/create");
            exit();
        }

        // Generate Slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
        // Cek duplikasi slug, jika ada tambahkan random string
        if ($this->eventModel->findBySlug($slug)) {
            $slug .= '-' . substr(bin2hex(random_bytes(2)), 0, 4);
        }

        // Handle Upload Banner
        $bannerName = null;
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['banner']['tmp_name'];
            $fileName = $_FILES['banner']['name'];
            $fileSize = $_FILES['banner']['size'];
            $fileType = $_FILES['banner']['type'];
            
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['error'] = 'Ekstensi file banner tidak diizinkan. Gunakan JPG, PNG, atau WEBP.';
                header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/create");
                exit();
            }
            
            if ($fileSize > 2097152) { // 2MB
                $_SESSION['error'] = 'Ukuran file banner maksimal 2MB.';
                header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/create");
                exit();
            }

            // Sanitize file name & generate unique name
            $bannerName = 'banner_' . time() . '_' . md5(uniqid()) . '.' . $fileExtension;
            
            $uploadFileDir = dirname(dirname(__DIR__)) . '/public/uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $bannerName;
            
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                $_SESSION['error'] = 'Gagal mengupload banner event ke server.';
                header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/create");
                exit();
            }
        }

        $success = $this->eventModel->create($judul, $slug, $deskripsi, $lokasi, $tanggal_event, $waktu_event, $kuota, $bannerName, $adminId);

        if ($success) {
            $_SESSION['success'] = 'Event baru berhasil ditambahkan!';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/events");
        } else {
            $_SESSION['error'] = 'Gagal menyimpan event baru.';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/create");
        }
        exit();
    }

    public function editEvent($id) {
        $event = $this->eventModel->findById($id);
        
        if (!$event) {
            http_response_code(404);
            $errorCode = 404;
            $errorMessage = "Event tidak ditemukan.";
            include dirname(dirname(__DIR__)) . '/app/views/errors/error.php';
            exit();
        }

        $pageTitle = 'Edit Event - ' . $event['judul'];
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/admin/events/edit.php';
    }

    public function updateEvent($id) {
        $event = $this->eventModel->findById($id);
        
        if (!$event) {
            $_SESSION['error'] = 'Event tidak ditemukan.';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/events");
            exit();
        }

        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $tanggal_event = $_POST['tanggal_event'] ?? '';
        $waktu_event = $_POST['waktu_event'] ?? '';
        $kuota = (int)($_POST['kuota'] ?? 0);
        $status = $_POST['status'] ?? 'open';

        if (empty($judul) || empty($deskripsi) || empty($lokasi) || empty($tanggal_event) || empty($waktu_event) || $kuota <= 0) {
            $_SESSION['error'] = 'Semua field wajib diisi dengan benar.';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/edit/" . $id);
            exit();
        }

        // Generate Slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
        // Cek duplikasi slug
        $existing = $this->eventModel->findBySlug($slug);
        if ($existing && $existing['id'] != $id) {
            $slug .= '-' . substr(bin2hex(random_bytes(2)), 0, 4);
        }

        // Handle Banner Update
        $bannerName = $event['banner'];
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['banner']['tmp_name'];
            $fileName = $_FILES['banner']['name'];
            $fileSize = $_FILES['banner']['size'];
            
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['error'] = 'Ekstensi banner tidak diizinkan. Gunakan JPG, PNG, atau WEBP.';
                header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/edit/" . $id);
                exit();
            }
            
            if ($fileSize > 2097152) {
                $_SESSION['error'] = 'Ukuran file banner maksimal 2MB.';
                header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/edit/" . $id);
                exit();
            }

            // Hapus banner lama jika ada
            $uploadFileDir = dirname(dirname(__DIR__)) . '/public/uploads/';
            if (!empty($event['banner']) && file_exists($uploadFileDir . $event['banner'])) {
                unlink($uploadFileDir . $event['banner']);
            }

            // Upload banner baru
            $bannerName = 'banner_' . time() . '_' . md5(uniqid()) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $bannerName;
            
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                $_SESSION['error'] = 'Gagal mengupload banner event baru.';
                header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/edit/" . $id);
                exit();
            }
        }

        $success = $this->eventModel->update($id, $judul, $slug, $deskripsi, $lokasi, $tanggal_event, $waktu_event, $kuota, $bannerName, $status);

        if ($success) {
            $_SESSION['success'] = 'Event berhasil diperbarui!';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/events");
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data event.';
            header("Location: " . AuthHelper::getBaseUrl() . "/admin/event/edit/" . $id);
        }
        exit();
    }

    public function deleteEvent($id) {
        $event = $this->eventModel->findById($id);
        
        if ($event) {
            // Hapus file banner jika ada
            $uploadFileDir = dirname(dirname(__DIR__)) . '/public/uploads/';
            if (!empty($event['banner']) && file_exists($uploadFileDir . $event['banner'])) {
                unlink($uploadFileDir . $event['banner']);
            }

            $this->eventModel->delete($id);
            $_SESSION['success'] = 'Event berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Event tidak ditemukan.';
        }
        header("Location: " . AuthHelper::getBaseUrl() . "/admin/events");
        exit();
    }

    public function scan() {
        $pageTitle = 'QR Scanner Kehadiran';
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/admin/scan.php';
    }

    public function peserta() {
        $pageTitle = 'Monitoring Kehadiran & Peserta';
        
        $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
        $search = trim($_GET['q'] ?? '');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Ambil daftar event untuk dropdown filter
        $events = $this->eventModel->all();
        
        // Ambil data pendaftaran berfilter & terpaginasi
        $registrations = $this->pendaftaranModel->getPaginatedPendaftaran($eventId, $search, $limit, $offset);
        $totalItems = $this->pendaftaranModel->getPendaftaranCount($eventId, $search);
        
        $totalPages = ceil($totalItems / $limit);
        $totalPages = max(1, $totalPages);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/admin/peserta.php';
    }

    public function exportPeserta() {
        $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
        $data = $this->pendaftaranModel->getExportData($eventId);
        
        $filename = "data_peserta_event_" . date('Ymd_His') . ".xls";
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        echo "<table border='1'>";
        echo "<tr style='background-color: #4f46e5; color: white;'>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>No WhatsApp</th>
                <th>Instansi</th>
                <th>Nama Event</th>
                <th>Status Check-In</th>
                <th>Waktu Check-In</th>
                <th>Tanggal Daftar</th>
              </tr>";
              
        $no = 1;
        foreach ($data as $row) {
            $waktuCheckin = $row['waktu_checkin'] ? date('d-m-Y H:i:s', strtotime($row['waktu_checkin'])) : '-';
            $tglDaftar = date('d-m-Y H:i:s', strtotime($row['tanggal_daftar']));
            
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['kode_tiket']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_peserta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email_peserta']) . "</td>";
            // Prefix quote to prevent excel from trimming leading zeros in phone numbers
            echo "<td>'" . htmlspecialchars($row['no_hp_peserta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['instansi_peserta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_event']) . "</td>";
            echo "<td>" . htmlspecialchars(strtoupper($row['status_checkin'])) . "</td>";
            echo "<td>" . $waktuCheckin . "</td>";
            echo "<td>" . $tglDaftar . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit();
    }

    public function exportAttendance() {
        $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
        $data = $this->logAbsensiModel->getExportAttendanceData($eventId);
        
        $filename = "log_absensi_event_" . date('Ymd_His') . ".xls";
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        echo "<table border='1'>";
        echo "<tr style='background-color: #10b981; color: white;'>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Instansi</th>
                <th>Nama Event</th>
                <th>Waktu Scan</th>
                <th>Status Scan</th>
                <th>Catatan</th>
                <th>Discan Oleh</th>
              </tr>";
              
        $no = 1;
        foreach ($data as $row) {
            $waktuScan = date('d-m-Y H:i:s', strtotime($row['waktu_scan']));
            
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['kode_tiket'] ?: '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_peserta'] ?: '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['email_peserta'] ?: '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['instansi_peserta'] ?: '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_event'] ?: '-') . "</td>";
            echo "<td>" . $waktuScan . "</td>";
            echo "<td>" . htmlspecialchars(strtoupper($row['status'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['catatan'] ?: '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_admin'] ?: '-') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit();
    }
}
