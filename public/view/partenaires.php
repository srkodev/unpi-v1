<?php 
// Initialisation des variables si elles n'existent pas déjà
if (!isset($partenaires)) {
    $partenaires = [];
}

include __DIR__ . '/../include/header.php'; ?>

<main class="partenaires-section">
    <div class="container">
        <div class="partenaires-header">
            <h1>Nos Partenaires</h1>
            <p>Découvrez nos partenaires qui nous accompagnent dans notre mission de service public</p>
        </div>

        <div class="partenaires-grid">
            <?php foreach ($partenaires as $partenaire): ?>
                <div class="partenaire-card">
                    <div class="partenaire-logo">
                        <img src="/<?= $partenaire['logo_url'] ?>" alt="Logo <?= htmlspecialchars($partenaire['nom']) ?>">
                    </div>
                    <div class="partenaire-content">
                        <h3><?= htmlspecialchars($partenaire['nom']) ?></h3>
                        <p><?= htmlspecialchars($partenaire['description'] ?? '') ?></p>
                        <?php if ($partenaire['site_url']): ?>
                            <a href="<?= $partenaire['site_url'] ?>" class="partenaire-website" target="_blank">
                                Visiter le site <i class="fas fa-external-link-alt"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="partenaire-contact">
            <h2>Devenir Partenaire</h2>
            <p>Vous souhaitez devenir partenaire de l'UNPI ? Contactez-nous pour en discuter.</p>
            <a href="/index.php/contact" class="contact-btn">Nous Contacter</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../include/footer.php'; ?> 