<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - UNPI</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../includes/header.php'; ?>

    <main class="container">
        <div class="error-page">
            <h1>404 - Page non trouvée</h1>
            <p>Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
            <div class="error-actions">
                <a href="/" class="btn btn-primary">Retour à l'accueil</a>
                <a href="/contact" class="btn btn-secondary">Nous contacter</a>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html> 