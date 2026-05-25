<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\MailerHelper;
use App\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        AuthHelper::redirectIfLoggedIn();
        
        $pageTitle = 'Masuk Akun';
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        
        unset($_SESSION['error'], $_SESSION['success']);
        
        include dirname(dirname(__DIR__)) . '/app/views/auth/login.php';
    }

    public function login() {
        AuthHelper::redirectIfLoggedIn();
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $redirect = trim($_GET['redirect'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email dan password wajib diisi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/login" . (!empty($redirect) ? "?redirect=" . urlencode($redirect) : ""));
            exit();
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Email atau password salah.';
            header("Location: " . AuthHelper::getBaseUrl() . "/login" . (!empty($redirect) ? "?redirect=" . urlencode($redirect) : ""));
            exit();
        }

        if (!$user['is_verified']) {
            $this->sendOtpAndRedirect($email);
            exit();
        }

        // Login user
        AuthHelper::login($user);

        // Redirect based on role or original target
        if (!empty($redirect)) {
            header("Location: " . $redirect);
        } else {
            if ($user['role'] === 'admin') {
                header("Location: " . AuthHelper::getBaseUrl() . "/admin");
            } else {
                header("Location: " . AuthHelper::getBaseUrl() . "/dashboard");
            }
        }
        exit();
    }

    public function showRegister() {
        AuthHelper::redirectIfLoggedIn();
        
        $pageTitle = 'Daftar Akun Baru';
        $error = $_SESSION['error'] ?? null;
        
        unset($_SESSION['error']);
        
        include dirname(dirname(__DIR__)) . '/app/views/auth/register.php';
    }

    public function register() {
        AuthHelper::redirectIfLoggedIn();
        
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $no_hp = trim($_POST['no_hp'] ?? '');
        $instansi = trim($_POST['instansi'] ?? '');

        if (empty($nama) || empty($email) || empty($password) || empty($no_hp) || empty($instansi)) {
            $_SESSION['error'] = 'Semua field wajib diisi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/register");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header("Location: " . AuthHelper::getBaseUrl() . "/register");
            exit();
        }

        // Cek email duplikat
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email sudah terdaftar. Gunakan email lain.';
            header("Location: " . AuthHelper::getBaseUrl() . "/register");
            exit();
        }

        // Simpan user
        $success = $this->userModel->create($nama, $email, $password, $no_hp, $instansi);

        if ($success) {
            $this->sendOtpAndRedirect($email);
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/register");
        }
        exit();
    }

    private function sendOtpAndRedirect($email) {
        $otp = (string) rand(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        $this->userModel->updateOtp($email, $otp, $expiresAt);
        
        $mailer = new MailerHelper();
        if ($mailer->sendOtp($email, $otp)) {
            $_SESSION['verify_email'] = $email;
            $_SESSION['success'] = 'Kode OTP telah dikirim ke email Anda.';
            header("Location: " . AuthHelper::getBaseUrl() . "/verify-otp");
        } else {
            $_SESSION['error'] = 'Gagal mengirim email OTP. Silakan coba lagi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/login");
        }
    }

    public function showVerifyOtp() {
        AuthHelper::redirectIfLoggedIn();

        if (empty($_SESSION['verify_email'])) {
            header("Location: " . AuthHelper::getBaseUrl() . "/login");
            exit();
        }

        $pageTitle = 'Verifikasi OTP';
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        
        unset($_SESSION['error'], $_SESSION['success']);
        
        include dirname(dirname(__DIR__)) . '/app/views/auth/verify_otp.php';
    }

    public function processVerifyOtp() {
        AuthHelper::redirectIfLoggedIn();

        $email = $_SESSION['verify_email'] ?? '';
        $otp = trim($_POST['otp'] ?? '');

        if (empty($email) || empty($otp)) {
            $_SESSION['error'] = 'Kode OTP wajib diisi.';
            header("Location: " . AuthHelper::getBaseUrl() . "/verify-otp");
            exit();
        }

        if ($this->userModel->verifyOtp($email, $otp)) {
            // Valid OTP
            $this->userModel->markAsVerified($email);
            $user = $this->userModel->findByEmail($email);
            AuthHelper::login($user);
            unset($_SESSION['verify_email']);

            $_SESSION['success'] = 'Email berhasil diverifikasi! Anda telah masuk.';
            header("Location: " . AuthHelper::getBaseUrl() . "/dashboard");
        } else {
            $_SESSION['error'] = 'Kode OTP salah atau sudah kedaluwarsa.';
            header("Location: " . AuthHelper::getBaseUrl() . "/verify-otp");
        }
        exit();
    }

    public function resendOtp() {
        AuthHelper::redirectIfLoggedIn();

        $email = $_SESSION['verify_email'] ?? '';
        if (empty($email)) {
            header("Location: " . AuthHelper::getBaseUrl() . "/login");
            exit();
        }

        $this->sendOtpAndRedirect($email);
        exit();
    }

    public function logout() {
        AuthHelper::logout();
        header("Location: " . AuthHelper::getBaseUrl() . "/login");
        exit();
    }
}
