<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../app/config/autoload.php';
require_once __DIR__ . '/../../../app/config/config.php';

use App\Controller\AdminController;
use App\Controller\AdminBienController;

// Vérification de l'authentification
$adminController = new AdminController();
$adminController->requireLogin();

// Récupération de l'ID du bien à supprimer
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $controller = new AdminBienController();
    $controller->delete($id);
} else {
    // Redirection vers la liste des biens si l'ID n'est pas valide
    header('Location: /index.php/admin/biens');
    exit;
} 