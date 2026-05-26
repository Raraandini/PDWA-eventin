<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Pendaftaran;
use App\Models\User;

class DashboardController {
    private $pendaftaranModel;
    private $userModel;

    public function __construct() {
        AuthHelper::requireLogin();
        $this->pendaftaranModel = new Pendaftaran();
        $this->userModel = new User();
    }

    public function index() {
        // Redireksi jika admin masuk ke route ini (admin punya dashboard sendiri)
        if (AuthHelper::isAdmin()) {
            header("Location: " . AuthHelper::getBaseUrl() . "/admin");
            exit();
        }

        $pageTitle = 'Dashboard Peserta';
        $userId = AuthHelper::getUserId();
        
        // Ambil riwayat pendaftaran event milik user
        $pendaftarans = $this->pendaftaranModel->findByUserId($userId);
        
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        
        unset($_SESSION['error'], $_SESSION['success']);
        
        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/dashboard/index.php';
    }

    public function profile() {
        $pageTitle = 'Profil Saya';
        $user = $this->userModel->findById(AuthHelper::getUserId());
        $pendaftarans = $this->pendaftaranModel->findByUserId(AuthHelper::getUserId());
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        $baseUrl = AuthHelper::getBaseUrl();
        include dirname(dirname(__DIR__)) . '/app/views/dashboard/profile.php';
    }

    public function updateProfile() {
        $userId = AuthHelper::getUserId();
        $currentUser = $this->userModel->findById($userId);

        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $instansi = trim($_POST['instansi'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';

        if (empty($nama) || empty($email) || empty($no_hp) || empty($instansi)) {
            $_SESSION['error'] = 'Semua informasi pribadi wajib diisi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/profile");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header("Location: " . AuthHelper::getBaseUrl() . "/profile");
            exit();
        }

        $existing = $this->userModel->findByEmail($email);
        if ($existing && $existing['id'] != $userId) {
            $_SESSION['error'] = 'Email sudah digunakan akun lain.';
            header("Location: " . AuthHelper::getBaseUrl() . "/profile");
            exit();
        }

        $success = $this->userModel->updateProfile($userId, $nama, $email, $no_hp, $instansi);

        if (!empty($password)) {
            if (strlen($password) < 6 || $password !== $passwordConfirm) {
                $_SESSION['error'] = 'Password minimal 6 karakter dan konfirmasi harus sama.';
                header("Location: " . AuthHelper::getBaseUrl() . "/profile");
                exit();
            }
            $success = $success && $this->userModel->updatePassword($userId, $password);
        }

        if ($success) {
            $_SESSION['nama'] = $nama;
            $_SESSION['email'] = $email;
            $_SESSION['success'] = 'Profil berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profil.';
        }

        header("Location: " . AuthHelper::getBaseUrl() . "/profile");
        exit();
    }
}
