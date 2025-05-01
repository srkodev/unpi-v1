<?php

// Autoloading
require_once __DIR__ . '/../app/config/autoload.php';

// Connexion à la BDD + injection dans les modèles
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/helpers.php';
require_once __DIR__ . '/../app/controller/ActualiteController.php';

// Import des contrôleurs nécessaires
use app\controller\ActualiteController;
use app\controller\BienController;
use app\controller\PartenaireController;

// Récupération de l'URL sans les paramètres
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Retirer le chemin de base /unpi/public
$basePath = '/unpi/public';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// Supprimer /index.php si présent
$uri = preg_replace('#^/index\.php#', '', $uri);

// Routeur simple
switch ($uri) {
    case '':
    case '/':
    case '/home':
        require __DIR__ . '/view/home.php';
        break;

    case '/actualites':
        (new ActualiteController())->index();
        break;

    case (preg_match('/^\/actualite\/(\d+)$/', $uri, $matches) ? true : false):
        $id = (int)$matches[1];
        (new ActualiteController())->show($id);
        break;

    case '/actualite-detail':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            (new ActualiteController())->show((int)$_GET['id']);
        } else {
            echo "ID d'actualité invalide.";
        }
        break;

    case '/adhesion':
        require __DIR__ . '/view/adhesion.php';
        break;

    case '/adherents':
        require __DIR__ . '/view/adherents.php';
        break;

    case '/biens':
        (new BienController())->index();
        break;

    case (preg_match('/^\/bien\/(\d+)$/', $uri, $matches) ? true : false):
        $id = (int)$matches[1];
        (new BienController())->show($id);
        break;

    case '/partenaires':
        $controller = new \App\Controller\PartenaireController();
        $controller->index();
        break;

    case '/contact':
        require __DIR__ . '/view/contact.php';
        break;

    case '/mentions-legales':
        require __DIR__ . '/view/mentions-legales.php';
        break;

    // Routes d'administration
    case '/admin/login':
        require __DIR__ . '/admin/login.php';
        break;

    case '/admin/dashboard':
        require __DIR__ . '/admin/admin.php';
        break;

    case '/admin/logout':
        require __DIR__ . '/admin/logout.php';
        break;

    case '/admin/actualites/liste_actualites':
        require __DIR__ . '/admin/actualites/liste_actualites.php';
        break;

    case '/admin/actualites/create':
        $controller = new \App\Controller\AdminActualiteController();
        $controller->create();
        break;

    case (preg_match('/^\/admin\/actualites\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $id = (int)$matches[1];
        $controller = new \App\Controller\AdminActualiteController();
        $controller->edit($id);
        break;

    case '/admin/actualites/delete':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $controller = new \App\Controller\AdminActualiteController();
            $controller->delete((int)$_GET['id']);
        }
        break;

    case '/admin/biens':
    case '/admin/biens/liste_biens':
        require __DIR__ . '/admin/biens/liste_biens.php';
        break;

    case '/admin/biens/create':
        $controller = new \App\Controller\AdminBienController();
        $controller->create();
        break;

    case (preg_match('/^\/admin\/biens\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $id = (int)$matches[1];
        $controller = new \App\Controller\AdminBienController();
        $controller->edit($id);
        break;

    case (preg_match('/^\/admin\/biens\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $id = (int)$matches[1];
        $controller = new \App\Controller\AdminBienController();
        $controller->delete($id);
        break;

    case (preg_match('/^\/admin\/biens\/set-primary-image\/(\d+)\/(\d+)$/', $uri, $matches) ? true : false):
        $bienId = (int)$matches[1];
        $imageId = (int)$matches[2];
        $controller = new \App\Controller\AdminBienController();
        $controller->setPrimaryImage($bienId, $imageId);
        break;

    case (preg_match('/^\/admin\/actualites\/set-primary-image\/(\d+)\/(\d+)$/', $uri, $matches) ? true : false):
        $actualiteId = (int)$matches[1];
        $imageId = (int)$matches[2];
        $controller = new \App\Controller\AdminActualiteController();
        $controller->setPrimaryImage($actualiteId, $imageId);
        break;

    case '/admin/partenaires':
        require __DIR__ . '/admin/partenaires/liste_partenaires.php';
        break;

    case '/admin/partenaires/create':
        require __DIR__ . '/admin/partenaires/form.php';
        break;

    case (preg_match('/^\/admin\/partenaires\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $_GET['id'] = (int)$matches[1];
        require __DIR__ . '/admin/partenaires/form.php';
        break;

    case (preg_match('/^\/admin\/partenaires\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $id = (int)$matches[1];
        $controller = new \App\Controller\AdminPartenaireController();
        $controller->delete($id);
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée : $uri";
        break;
}
