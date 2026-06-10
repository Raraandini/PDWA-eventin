<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create($nama, $email, $password, $no_hp, $instansi, $role = 'peserta') {
        $sql = "INSERT INTO user (nama, email, password, no_hp, instansi, role, is_verified) 
                VALUES (:nama, :email, :password, :no_hp, :instansi, :role, FALSE)";
        
        $stmt = $this->db->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        return $stmt->execute([
            ':nama' => htmlspecialchars(strip_tags($nama)),
            ':email' => htmlspecialchars(strip_tags($email)),
            ':password' => $hashedPassword,
            ':no_hp' => htmlspecialchars(strip_tags($no_hp)),
            ':instansi' => htmlspecialchars(strip_tags($instansi)),
            ':role' => $role
        ]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM user WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT id, nama, email, no_hp, instansi, role, is_verified, avatar, theme_color, dibuat_pada FROM user WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateOtp($email, $otp, $expiresAt) {
        $sql = "UPDATE user SET otp_code = :otp, otp_expires_at = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':otp' => $otp,
            ':email' => $email
        ]);
    }

    public function verifyOtp($email, $otp) {
        $sql = "SELECT id FROM user WHERE email = :email AND otp_code = :otp AND otp_expires_at >= NOW() LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':otp' => $otp
        ]);
        return $stmt->fetch() !== false;
    }

    public function markAsVerified($email) {
        $sql = "UPDATE user SET is_verified = TRUE, otp_code = NULL, otp_expires_at = NULL WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':email' => $email]);
    }

    public function getPaginatedPeserta($search = '', $limit = 10, $offset = 0) {
        $sql = "SELECT id, nama, email, no_hp, instansi, dibuat_pada 
                FROM user 
                WHERE role = 'peserta'";
        
        $params = [];
        if (!empty($search)) {
            $sql .= " AND (nama LIKE :search_nama OR email LIKE :search_email OR instansi LIKE :search_instansi OR no_hp LIKE :search_no_hp)";
            $searchTerm = "%$search%";
            $params[':search_nama'] = $searchTerm;
            $params[':search_email'] = $searchTerm;
            $params[':search_instansi'] = $searchTerm;
            $params[':search_no_hp'] = $searchTerm;
        }
        
        $sql .= " ORDER BY dibuat_pada DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters safely
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPesertaCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM user WHERE role = 'peserta'";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (nama LIKE :search_nama OR email LIKE :search_email OR instansi LIKE :search_instansi OR no_hp LIKE :search_no_hp)";
            $searchTerm = "%$search%";
            $params[':search_nama'] = $searchTerm;
            $params[':search_email'] = $searchTerm;
            $params[':search_instansi'] = $searchTerm;
            $params[':search_no_hp'] = $searchTerm;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function updateProfile($id, $nama, $email, $no_hp, $instansi, $avatar = null, $theme_color = 'lime') {
        if ($avatar !== null) {
            $sql = "UPDATE user 
                    SET nama = :nama, email = :email, no_hp = :no_hp, instansi = :instansi, avatar = :avatar, theme_color = :theme_color 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nama' => htmlspecialchars(strip_tags($nama)),
                ':email' => htmlspecialchars(strip_tags($email)),
                ':no_hp' => htmlspecialchars(strip_tags($no_hp)),
                ':instansi' => htmlspecialchars(strip_tags($instansi)),
                ':avatar' => htmlspecialchars(strip_tags($avatar)),
                ':theme_color' => htmlspecialchars(strip_tags($theme_color))
            ]);
        }

        $sql = "UPDATE user 
                SET nama = :nama, email = :email, no_hp = :no_hp, instansi = :instansi, theme_color = :theme_color 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nama' => htmlspecialchars(strip_tags($nama)),
            ':email' => htmlspecialchars(strip_tags($email)),
            ':no_hp' => htmlspecialchars(strip_tags($no_hp)),
            ':instansi' => htmlspecialchars(strip_tags($instansi)),
            ':theme_color' => htmlspecialchars(strip_tags($theme_color))
        ]);
    }

    public function updatePassword($id, $password) {
        $sql = "UPDATE user SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
    }

    public function countAllPeserta() {
        $sql = "SELECT COUNT(*) as total FROM user WHERE role = 'peserta'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function getPetugas() {
        $sql = "SELECT id, nama, email, no_hp, instansi, dibuat_pada FROM user WHERE role = 'petugas' ORDER BY dibuat_pada DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $sql = "DELETE FROM user WHERE id = :id AND role = 'petugas'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
