<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class LogAbsensi {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Create a log entry for attendance scans
     * 
     * @param int $pendaftaranId Registration ID (or 0 if invalid scan)
     * @param int|null $discanOleh Admin user ID who performed the scan
     * @param string $status 'berhasil', 'duplikat', 'invalid'
     * @param string|null $catatan Description or notes
     * @return bool
     */
    public function create($pendaftaranId, $discanOleh, $status, $catatan = null) {
        $sql = "INSERT INTO log_absensi (pendaftaran_id, discan_oleh, status, catatan) 
                VALUES (:pendaftaran_id, :discan_oleh, :status, :catatan)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pendaftaran_id' => $pendaftaranId,
            ':discan_oleh' => $discanOleh,
            ':status' => $status,
            ':catatan' => htmlspecialchars($catatan)
        ]);
    }

    public function getLatestLogs($limit = 10) {
        $sql = "SELECT l.*, p.kode_tiket, u_peserta.nama as nama_peserta, u_admin.nama as nama_admin, e.judul as nama_event 
                FROM log_absensi l 
                LEFT JOIN pendaftaran p ON l.pendaftaran_id = p.id 
                LEFT JOIN user u_peserta ON p.user_id = u_peserta.id 
                LEFT JOIN event e ON p.event_id = e.id 
                LEFT JOIN user u_admin ON l.discan_oleh = u_admin.id 
                ORDER BY l.waktu_scan DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getExportAttendanceData($eventId = null) {
        $sql = "SELECT p.kode_tiket, u_peserta.nama as nama_peserta, u_peserta.email as email_peserta, 
                       u_peserta.instansi as instansi_peserta, e.judul as nama_event, 
                       l.waktu_scan, l.status, l.catatan, u_admin.nama as nama_admin 
                FROM log_absensi l 
                INNER JOIN pendaftaran p ON l.pendaftaran_id = p.id 
                INNER JOIN user u_peserta ON p.user_id = u_peserta.id 
                INNER JOIN event e ON p.event_id = e.id 
                LEFT JOIN user u_admin ON l.discan_oleh = u_admin.id";
        
        $params = [];
        if ($eventId !== null && $eventId !== '') {
            $sql .= " WHERE p.event_id = :event_id";
            $params[':event_id'] = $eventId;
        }
        
        $sql .= " ORDER BY l.waktu_scan DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
