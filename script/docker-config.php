<?php
/**
 * docker-config.php – Configuration spécifique pour l'environnement Docker
 */

// Chemin vers le répertoire public
define('PUBLIC_PATH', realpath(__DIR__ . '/../public/') . '/');

// Utilisation des variables d'environnement Docker
const DB_HOST = 'db'; // Nom du service dans docker-compose
const DB_NAME = 'fdpci';
const DB_USER = 'fdpci_user';
const DB_PASS = 'ChangeMe#2025';

try {
    $pdo = new PDO(
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
    // Log l'erreur pour le débogage
    error_log('Erreur PDO: ' . $e->getMessage());
    exit('Erreur de connexion à la base de données.');
}

// Injection dans les modèles
\App\Models\BaseModel::init(DB_HOST, DB_NAME, DB_USER, DB_PASS);

// Rendre $pdo global
global $pdo; 