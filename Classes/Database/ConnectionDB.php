<?php

namespace Database;

use PDO;
use PDOException;

class ConnectionDB
{
    private static $conn;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$conn)) {
            try {
                self::$conn = new PDO(
                    'mysql:host=' . HOST . '; dbname=' . DATABASE . ';',
                    USER,
                    PASSWORD
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
            }
        }

        return self::$conn;
    }
}
