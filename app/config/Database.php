<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static $host = null;
    private static $db_name = null;
    private static $username = null;
    private static $password = null;
    private static $port = null;
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$host = $_ENV['DB_HOST'];
            self::$port = $_ENV['DB_PORT'];
            self::$db_name = $_ENV['DB_DATABASE'];
            self::$username = $_ENV['DB_USERNAME'];
            self::$password = $_ENV['DB_PASSWORD'];

            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db_name . ";charset=utf8mb4",
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
