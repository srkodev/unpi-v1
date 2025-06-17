<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../app/config/autoload.php';
require_once __DIR__ . '/../../../app/config/config.php';

use App\Controller\AdminController;
use App\Controller\AdminPartenaireController;

// Vérification de l'authentification
$adminController = new AdminController();
$adminController->requireLogin();

// Récupération de l'ID du partenaire à supprimer
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $controller = new AdminPartenaireController();
    $controller->delete($id);
} else {
    // Redirection vers la liste des partenaires si l'ID n'est pas valide
    header('Location: /index.php/admin/partenaires');
    exit;
} 