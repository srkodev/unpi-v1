<?php
/**
 * config.php – Connexion PDO centralisée
 */

// Chemin vers le répertoire public
define('PUBLIC_PATH', realpath(__DIR__ . '/../../public/') . '/');

const DB_HOST = 'localhost';
const DB_NAME = 'fdpci';
const DB_USER = 'fdpci_user';
const DB_PASS = 'ChangeMe#2025';

// Configuration Resend pour l'envoi d'emails
define('RESEND_API_KEY', $_ENV['RESEND_API_KEY'] ?? 're_dASj4Azj_H1HzdTTYwKdpdtuE3cRqqRcb');
define('CONTACT_FROM_EMAIL', $_ENV['CONTACT_FROM_EMAIL'] ?? 'no-reply@vosoft.fr');
define('CONTACT_TO_EMAIL', $_ENV['CONTACT_TO_EMAIL'] ?? 'srko.dj@gmail.com'); // CHANGEZ CET EMAIL
define('CONTACT_FROM_NAME', $_ENV['CONTACT_FROM_NAME'] ?? 'Site FDPCI Aube');

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
    exit('Erreur de connexion à la base de données.');
}

// Injection dans les modèles
\App\Models\BaseModel::init(DB_HOST, DB_NAME, DB_USER, DB_PASS);

// Rendre $pdo global
global $pdo;
