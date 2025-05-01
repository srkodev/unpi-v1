<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../app/config/autoload.php';
require_once __DIR__ . '/../../../app/config/config.php';

use App\Controller\AdminController;
use App\Controller\AdminActualiteController;

// Vérification de l'authentification
$adminController = new AdminController();
$adminController->requireLogin();

// Log des données POST
error_log("Données POST reçues: " . print_r($_POST, true));
error_log("Données FILES reçues: " . print_r($_FILES, true));

$controller = new AdminActualiteController();
$isEdit = isset($actualite);
$title = $isEdit ? 'Modifier une actualité' : 'Nouvelle actualité';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - <?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
        }
        .sidebar .nav-link:hover {
            color: rgba(255,255,255,1);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,.1);
        }
        .main-content {
            padding: 20px;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4>Administration</h4>
                    <a href="/index.php" class="btn btn-sm btn-light mb-3">
                        <i class="bi bi-house-door"></i> Retour au site
                    </a>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/dashboard">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/biens">
                                <i class="bi bi-house"></i> Biens
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/index.php/admin/actualites/liste_actualites">
                                <i class="bi bi-newspaper"></i> Actualités
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/partenaires">
                                <i class="bi bi-people"></i> Partenaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/logout">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?php echo $title; ?></h2>
                    <a href="/index.php/admin/actualites/liste_actualites" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre" required
                                    value="<?php echo $isEdit ? htmlspecialchars($actualite['titre']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <select class="form-select" id="categorie" name="categorie" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="juridique" <?php echo $isEdit && $actualite['categorie'] === 'juridique' ? 'selected' : ''; ?>>Juridique</option>
                                    <option value="formation" <?php echo $isEdit && $actualite['categorie'] === 'formation' ? 'selected' : ''; ?>>Formation</option>
                                    <option value="evenement" <?php echo $isEdit && $actualite['categorie'] === 'evenement' ? 'selected' : ''; ?>>Événement</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="extrait" class="form-label">Extrait</label>
                                <textarea class="form-control" id="extrait" name="extrait" rows="3"><?php echo $isEdit ? htmlspecialchars($actualite['extrait']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="contenu" class="form-label">Contenu</label>
                                <textarea class="form-control" id="contenu" name="contenu" rows="10"><?php echo $isEdit ? htmlspecialchars($actualite['contenu']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="publie_le" class="form-label">Date de publication</label>
                                <input type="date" class="form-control" id="publie_le" name="publie_le"
                                    value="<?php echo $isEdit ? $actualite['publie_le'] : date('Y-m-d'); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Images</label>
                                <div class="row">
                                    <?php if ($isEdit && !empty($images)): ?>
                                        <?php foreach ($images as $image): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <img src="<?= htmlspecialchars($image['url']) ?>" class="card-img-top" alt="Image de l'actualité">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <a href="/index.php/admin/actualites/set-primary-image/<?= $actualite['id'] ?>/<?= $image['id'] ?>" 
                                                               class="btn btn-sm <?= $image['is_primary'] ? 'btn-success' : 'btn-outline-success' ?>">
                                                                <?= $image['is_primary'] ? 'Image principale' : 'Définir comme principale' ?>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger delete-image" 
                                                                    data-image-id="<?= $image['id'] ?>">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $isEdit ? 'Modifier' : 'Créer'; ?> l'actualité
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contenu').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
</body>
</html> 