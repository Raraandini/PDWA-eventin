<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static $host = '127.0.0.1';
    private static $db_name = 'manajemen_event';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $exception) {
                die("Koneksi database gagal: " . $exception->getMessage());
            }
        }
        return self::$conn;
    }
}
