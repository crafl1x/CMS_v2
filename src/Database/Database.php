<?php

namespace App\Database;

use PDO;
use PDOException;

class Database {

    private static ?PDO $instance = null;

    public static function connect() {

        if (self::$instance === null) {

            $db_host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $db_user = $_ENV['DB_USER'] ?? 'root';
            $db_password = $_ENV['DB_PASSWORD'] ?? 'root';
            $db_name = $_ENV['DB_NAME'] ?? 'cms';

            $dsn = "mysql:host=$db_host;dbname=$db_name";

            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

        
            try {
                self::$instance = new PDO($dsn, $db_user, $db_password, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
        }

        return self::$instance;

    }
}

?>