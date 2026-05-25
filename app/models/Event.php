<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Event {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create($judul, $slug, $deskripsi, $lokasi, $tanggal_event, $waktu_event, $kuota, $banner, $dibuat_oleh) {
        $sql = "INSERT INTO event (judul, slug, deskripsi, lokasi, tanggal_event, waktu_event, kuota, banner, status, dibuat_oleh) 
                VALUES (:judul, :slug, :deskripsi, :lokasi, :tanggal_event, :waktu_event, :kuota, :banner, 'open', :dibuat_oleh)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':judul' => htmlspecialchars(strip_tags($judul)),
            ':slug' => htmlspecialchars(strip_tags($slug)),
            ':deskripsi' => htmlspecialchars($deskripsi), // Allow some text layout, sanitize on display if needed or keep safe
            ':lokasi' => htmlspecialchars(strip_tags($lokasi)),
            ':tanggal_event' => $tanggal_event,
            ':waktu_event' => $waktu_event,
            ':kuota' => (int)$kuota,
            ':banner' => $banner,
            ':dibuat_oleh' => $dibuat_oleh
        ]);
    }

    public function update($id, $judul, $slug, $deskripsi, $lokasi, $tanggal_event, $waktu_event, $kuota, $banner, $status) {
        $sql = "UPDATE event 
                SET judul = :judul, slug = :slug, deskripsi = :deskripsi, lokasi = :lokasi, 
                    tanggal_event = :tanggal_event, waktu_event = :waktu_event, kuota = :kuota, 
                    banner = :banner, status = :status 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':judul' => htmlspecialchars(strip_tags($judul)),
            ':slug' => htmlspecialchars(strip_tags($slug)),
            ':deskripsi' => htmlspecialchars($deskripsi),
            ':lokasi' => htmlspecialchars(strip_tags($lokasi)),
            ':tanggal_event' => $tanggal_event,
            ':waktu_event' => $waktu_event,
            ':kuota' => (int)$kuota,
            ':banner' => $banner,
            ':status' => $status
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM event WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findById($id) {
        $sql = "SELECT e.*, u.nama as pembuat 
                FROM event e 
                LEFT JOIN user u ON e.dibuat_oleh = u.id 
                WHERE e.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug) {
        $sql = "SELECT e.*, u.nama as pembuat 
                FROM event e 
                LEFT JOIN user u ON e.dibuat_oleh = u.id 
                WHERE e.slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function all() {
        $sql = "SELECT e.*, u.nama as pembuat, 
                (SELECT COUNT(*) FROM pendaftaran p WHERE p.event_id = e.id) as terdaftar 
                FROM event e 
                LEFT JOIN user u ON e.dibuat_oleh = u.id 
                ORDER BY e.dibuat_pada DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function allActive() {
        $sql = "SELECT e.*, u.nama as pembuat,
                (SELECT COUNT(*) FROM pendaftaran p WHERE p.event_id = e.id) as terdaftar 
                FROM event e 
                LEFT JOIN user u ON e.dibuat_oleh = u.id 
                WHERE e.status = 'open' 
                ORDER BY e.tanggal_event ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getRemainingKuota($id) {
        $event = $this->findById($id);
        if (!$event) return 0;
        
        $sql = "SELECT COUNT(*) as terdaftar FROM pendaftaran WHERE event_id = :event_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':event_id' => $id]);
        $result = $stmt->fetch();
        
        $remaining = $event['kuota'] - $result['terdaftar'];
        return max(0, $remaining);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM event";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function countActive() {
        $sql = "SELECT COUNT(*) as total FROM event WHERE status = 'open'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
}
