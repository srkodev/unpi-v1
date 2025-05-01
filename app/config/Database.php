<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    protected $conn;

    public function __construct()
    {
        global $pdo;
        
        if ($pdo instanceof PDO) {
            $this->conn = $pdo;
        } else {
            try {
                $this->conn = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                exit('Erreur de connexion à la base de données.');
            }
        }
    }
} 