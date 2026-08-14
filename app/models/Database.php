<?php
class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance) return self::$instance;

        $cfg = require __DIR__ . '/../../config/database.php';
        try {
            $dsn = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset=utf8mb4";
            self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return self::$instance;
        } catch (PDOException $e) {
            throw new Exception('Error DB: ' . $e->getMessage());
        }
    }
}
