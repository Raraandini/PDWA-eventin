<?php

namespace App\Helpers;

class AuthHelper {
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session cookie configurations
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            
            // If running on HTTPS, set secure cookie (optional, depends on environment)
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            
            session_start();
        }
    }

   
    public static function generateCsrfToken() {
        self::startSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function getCsrfInput() {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    public static function requireCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::startSession();
            $headers = getallheaders();
            $token = $_POST['csrf_token'] ?? $headers['X-CSRF-TOKEN'] ?? '';
            
            if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
                // If it is a JSON request, return JSON response
                if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                    http_response_code(403);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Token CSRF tidak valid. Silakan muat ulang halaman.'
                    ]);
                    exit();
                } else {
                    $_SESSION['error'] = 'Sesi form Anda telah kedaluwarsa atau tidak valid (CSRF). Silakan coba lagi.';
                    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? self::getBaseUrl()));
                    exit();
                }
            }
        }
    }
    // -----------------------

    public static function login($user) {
        self::startSession();
        // Prevent session fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['avatar'] = $user['avatar'] ?? null;
        $_SESSION['theme_color'] = $user['theme_color'] ?? 'lime';
    }

    public static function logout() {
        self::startSession();
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        
        session_destroy();
    }

    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        self::startSession();
        return self::isLoggedIn() && $_SESSION['role'] === 'admin';
    }

    public static function isPetugas() {
        self::startSession();
        return self::isLoggedIn() && $_SESSION['role'] === 'petugas';
    }

    public static function isStaff() {
        self::startSession();
        return self::isLoggedIn() && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'petugas');
    }

    public static function getUserId() {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUserName() {
        self::startSession();
        return $_SESSION['nama'] ?? 'Guest';
    }

    public static function getUserRole() {
        self::startSession();
        return $_SESSION['role'] ?? null;
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            // Keep redirect target in query parameter
            $redirect = $_SERVER['REQUEST_URI'];
            header("Location: " . self::getBaseUrl() . "/login?redirect=" . urlencode($redirect));
            exit();
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            $errorCode = 403;
            $errorMessage = "Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.";
            
            $errorFile = __DIR__ . '/../views/errors/error.php';
            if (file_exists($errorFile)) {
                include $errorFile;
            } else {
                echo "<h1>Error 403</h1><p>Akses Ditolak</p>";
            }
            exit();
        }
    }

    public static function requireStaff() {
        self::requireLogin();
        if (!self::isStaff()) {
            http_response_code(403);
            $errorCode = 403;
            $errorMessage = "Akses Ditolak: Anda bukan Admin atau Petugas.";
            
            $errorFile = __DIR__ . '/../views/errors/error.php';
            if (file_exists($errorFile)) {
                include $errorFile;
            } else {
                echo "<h1>Error 403</h1><p>Akses Ditolak</p>";
            }
            exit();
        }
    }

    public static function redirectIfLoggedIn() {
        if (self::isLoggedIn()) {
            $role = self::getUserRole();
            if ($role === 'admin') {
                header("Location: " . self::getBaseUrl() . "/admin");
            } else {
                header("Location: " . self::getBaseUrl() . "/dashboard");
            }
            exit();
        }
    }

    public static function getBaseUrl() {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        $basePath = str_replace('\\', '/', $basePath);
        return ($basePath === '/') ? '' : $basePath;
    }
}
