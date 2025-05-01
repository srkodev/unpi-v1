<?php
session_start();
require_once __DIR__ . '/../../app/config/autoload.php';
require_once __DIR__ . '/../../app/config/config.php';

use App\Controller\AdminController;

$adminController = new AdminController();
$adminController->requireLogin();

// Récupération des statistiques
try {
    // Nombre total de biens
    $stmt = $pdo->query("SELECT COUNT(*) FROM biens");
    $total_biens = $stmt->fetchColumn();

    // Nombre total d'actualités
    $stmt = $pdo->query("SELECT COUNT(*) FROM actualites");
    $total_actualites = $stmt->fetchColumn();

    // Nombre total de partenaires
    $stmt = $pdo->query("SELECT COUNT(*) FROM partenaires");
    $total_partenaires = $stmt->fetchColumn();

    // Derniers biens ajoutés
    $stmt = $pdo->query("SELECT * FROM biens ORDER BY created_at DESC LIMIT 5");
    $derniers_biens = $stmt->fetchAll();

    // Dernières actualités
    $stmt = $pdo->query("SELECT * FROM actualites ORDER BY created_at DESC LIMIT 5");
    $dernieres_actualites = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = 'Une erreur est survenue lors de la récupération des données';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
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
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
                            <a class="nav-link active" href="/index.php/admin/dashboard">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/biens">
                                <i class="bi bi-house"></i> Biens
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php/admin/actualites/liste_actualites">
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
                    <h2>Tableau de bord</h2>
                    <div>
                        Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_email']); ?>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card bg-primary text-white">
                            <h3><?php echo $total_biens; ?></h3>
                            <p class="mb-0">Biens immobiliers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-success text-white">
                            <h3><?php echo $total_actualites; ?></h3>
                            <p class="mb-0">Actualités</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card bg-info text-white">
                            <h3><?php echo $total_partenaires; ?></h3>
                            <p class="mb-0">Partenaires</p>
                        </div>
                    </div>
                </div>

                <!-- Derniers biens -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Derniers biens ajoutés</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Prix</th>
                                        <th>Date d'ajout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($derniers_biens as $bien): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($bien['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($bien['type']); ?></td>
                                        <td><?php echo number_format($bien['prix'], 2, ',', ' '); ?> €</td>
                                        <td><?php echo date('d/m/Y', strtotime($bien['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Dernières actualités -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Dernières actualités</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Date de publication</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dernieres_actualites as $actualite): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($actualite['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($actualite['categorie']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($actualite['publie_le'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 