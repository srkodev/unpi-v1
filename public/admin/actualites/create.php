<?php
$rootPath = realpath(__DIR__ . '/../../../../');
require_once $rootPath . '/app/config/config.php';
require_once $rootPath . '/app/controller/AdminActualiteController.php';

// Vérification de l'authentification
if (!isset($_SESSION['admin_id'])) {
    header('Location: /index.php/admin/login');
    exit;
}

// Initialisation du contrôleur
$controller = new \App\Controller\AdminActualiteController();

// Si c'est une requête POST, traiter la création
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->create();
} else {
    // Sinon, afficher le formulaire
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Créer une actualité - Administration</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            .form-container {
                max-width: 800px;
                margin: 2rem auto;
                padding: 2rem;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .required-field::after {
                content: " *";
                color: red;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/index.php/admin">Administration</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/actualites">Actualités</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/logout">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="form-container">
                <h1 class="mb-4">Créer une actualité</h1>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="/index.php/admin/actualites/create" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="titre" class="form-label required-field">Titre</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>

                    <div class="mb-3">
                        <label for="categorie" class="form-label required-field">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="juridique">Juridique</option>
                            <option value="formation">Formation</option>
                            <option value="evenement">Événement</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="contenu" class="form-label required-field">Contenu</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="10" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="publie_le" class="form-label">Date de publication</label>
                        <input type="date" class="form-control" id="publie_le" name="publie_le">
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">Vous pouvez sélectionner plusieurs images. Formats acceptés : JPG, PNG, GIF.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/index.php/admin/actualites" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer l'actualité</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?> 