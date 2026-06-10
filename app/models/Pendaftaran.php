<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Pendaftaran {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create($userId, $eventId, $kodeTiket, $tokenQr) {
        $sql = "INSERT INTO pendaftaran (user_id, event_id, kode_tiket, token_qr, status_checkin, tanggal_daftar) 
                VALUES (:user_id, :event_id, :kode_tiket, :token_qr, 'pending', CURRENT_TIMESTAMP)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':event_id' => $eventId,
            ':kode_tiket' => $kodeTiket,
            ':token_qr' => $tokenQr
        ]);
    }

    public function checkRegistered($userId, $eventId) {
        $sql = "SELECT COUNT(*) as total FROM pendaftaran WHERE user_id = :user_id AND event_id = :event_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':event_id' => $eventId
        ]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    public function findByTokenQr($tokenQr) {
        $sql = "SELECT p.*, u.nama as nama_peserta, u.email as email_peserta, u.instansi as instansi_peserta, 
                       e.judul as nama_event, e.tanggal_event, e.waktu_event, e.lokasi 
                FROM pendaftaran p 
                INNER JOIN user u ON p.user_id = u.id 
                INNER JOIN event e ON p.event_id = e.id 
                WHERE p.token_qr = :token_qr LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token_qr' => $tokenQr]);
        return $stmt->fetch();
    }

    public function findByKodeTiket($kodeTiket) {
        $sql = "SELECT p.*, u.nama as nama_peserta, u.email as email_peserta, u.no_hp as no_hp_peserta, u.instansi as instansi_peserta, 
                       e.judul as nama_event, e.tanggal_event, e.waktu_event, e.lokasi, e.banner as event_banner 
                FROM pendaftaran p 
                INNER JOIN user u ON p.user_id = u.id 
                INNER JOIN event e ON p.event_id = e.id 
                WHERE p.kode_tiket = :kode_tiket LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':kode_tiket' => $kodeTiket]);
        return $stmt->fetch();
    }

    public function findByUserId($userId) {
        $sql = "SELECT p.*, e.judul as nama_event, e.slug as event_slug, e.tanggal_event, e.waktu_event, e.lokasi, e.status as event_status 
                FROM pendaftaran p 
                INNER JOIN event e ON p.event_id = e.id 
                WHERE p.user_id = :user_id 
                ORDER BY p.tanggal_daftar DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getPaginatedPendaftaran($eventId = null, $search = '', $limit = 10, $offset = 0) {
        $sql = "SELECT p.*, u.nama as nama_peserta, u.email as email_peserta, u.instansi as instansi_peserta, u.no_hp as no_hp_peserta,
                       e.judul as nama_event 
                FROM pendaftaran p 
                INNER JOIN user u ON p.user_id = u.id 
                INNER JOIN event e ON p.event_id = e.id 
                WHERE 1=1";
        
        $params = [];
        
        if ($eventId !== null && $eventId !== '') {
            $sql .= " AND p.event_id = :event_id";
            $params[':event_id'] = $eventId;
        }
        
        if (!empty($search)) {
            $sql .= " AND (u.nama LIKE :search_nama OR u.email LIKE :search_email OR p.kode_tiket LIKE :search_kode_tiket OR u.instansi LIKE :search_instansi)";
            $searchTerm = "%$search%";
            $params[':search_nama'] = $searchTerm;
            $params[':search_email'] = $searchTerm;
            $params[':search_kode_tiket'] = $searchTerm;
            $params[':search_instansi'] = $searchTerm;
        }
        
        $sql .= " ORDER BY p.tanggal_daftar DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendaftaranCount($eventId = null, $search = '') {
        $sql = "SELECT COUNT(*) as total 
                FROM pendaftaran p 
                INNER JOIN user u ON p.user_id = u.id 
                WHERE 1=1";
        
        $params = [];
        
        if ($eventId !== null && $eventId !== '') {
            $sql .= " AND p.event_id = :event_id";
            $params[':event_id'] = $eventId;
        }
        
        if (!empty($search)) {
            $sql .= " AND (u.nama LIKE :search_nama OR u.email LIKE :search_email OR p.kode_tiket LIKE :search_kode_tiket OR u.instansi LIKE :search_instansi)";
            $searchTerm = "%$search%";
            $params[':search_nama'] = $searchTerm;
            $params[':search_email'] = $searchTerm;
            $params[':search_kode_tiket'] = $searchTerm;
            $params[':search_instansi'] = $searchTerm;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function checkIn($pendaftaranId, $status = 'hadir') {
        $sql = "UPDATE pendaftaran 
                SET status_checkin = :status, waktu_checkin = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $pendaftaranId
        ]);
    }

    public function updateCertificatePath($pendaftaranId, $path) {
        $sql = "UPDATE pendaftaran SET path_sertifikat = :path WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':path' => $path,
            ':id' => $pendaftaranId
        ]);
    }

    public function deleteByKodeTiket($kodeTiket, $userId) {
        $sql = "DELETE FROM pendaftaran WHERE kode_tiket = :kode_tiket AND user_id = :user_id AND status_checkin = 'pending'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':kode_tiket' => $kodeTiket,
            ':user_id' => $userId
        ]);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM pendaftaran";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function countHadir() {
        $sql = "SELECT COUNT(*) as total FROM pendaftaran WHERE status_checkin = 'hadir'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function getExportData($eventId = null) {
        $sql = "SELECT p.kode_tiket, u.nama as nama_peserta, u.email as email_peserta, 
                       u.no_hp as no_hp_peserta, u.instansi as instansi_peserta, 
                       e.judul as nama_event, p.status_checkin, p.waktu_checkin, p.tanggal_daftar 
                FROM pendaftaran p 
                INNER JOIN user u ON p.user_id = u.id 
                INNER JOIN event e ON p.event_id = e.id";
        
        $params = [];
        if ($eventId !== null && $eventId !== '') {
            $sql .= " WHERE p.event_id = :event_id";
            $params[':event_id'] = $eventId;
        }
        
        $sql .= " ORDER BY e.judul ASC, u.nama ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
