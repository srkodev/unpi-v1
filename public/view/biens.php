<?php
use App\Models\BienImage;
include __DIR__ . '/../include/header.php';

// Calculer le nombre total de pages
$total_biens = count($biens);
$biens_par_page = 6;
$total_pages = ceil($total_biens / $biens_par_page);

// Récupérer la page courante
$page_courante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page_courante = max(1, min($page_courante, $total_pages));

// Calculer les biens à afficher pour la page courante
$debut = ($page_courante - 1) * $biens_par_page;
$biens_page = array_slice($biens, $debut, $biens_par_page);
?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Nos biens</h1>
                <p>Découvrez notre sélection de biens immobiliers</p>
            </div>
        </section>

        <section class="properties-section">
            <div class="container">
                <div class="filters">
                    <button class="filter-btn active" data-type="all">Tous les biens</button>
                    <?php foreach ($types as $type): ?>
                        <button class="filter-btn" data-type="<?= $type ?>">
                            <?php
                            switch($type) {
                                case 'vente':
                                    echo 'À vendre';
                                    break;
                                case 'location':
                                    echo 'À louer';
                                    break;
                                case 'location_etudiante':
                                    echo 'Location étudiante';
                                    break;
                                default:
                                    echo ucfirst($type);
                            }
                            ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="properties-grid">
                    <?php foreach ($biens_page as $bien): ?>
                        <article class="property-card" data-type="<?= $bien['type'] ?>">
                            <div class="property-image">
                                <?php
                                $image = BienImage::getPrimaryImage($bien['id']);
                                if ($image) {
                                    $imageUrl = '/' . $image['url']; // Format: /uploads/biens/filename.jpg
                                } else {
                                    $imageUrl = 'https://picsum.photos/800/600?random=' . $bien['id'];
                                }
                                ?>
                                <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($bien['titre']) ?>" loading="lazy">
                            </div>
                            <div class="property-content">
                                <div class="property-meta">
                                    <span class="property-type"><?= ucfirst($bien['type']) ?></span>
                                    <?php if ($bien['prix']): ?>
                                        <span class="property-price"><?= number_format($bien['prix'], 0, ',', ' ') ?> €</span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="property-title"><?= htmlspecialchars($bien['titre']) ?></h3>
                                <div class="property-details">
                                    <?php if ($bien['surface_m2']): ?>
                                        <span><i class="fas fa-vector-square"></i> <?= $bien['surface_m2'] ?> m²</span>
                                    <?php endif; ?>
                                    <?php if ($bien['chambres']): ?>
                                        <span><i class="fas fa-bed"></i> <?= $bien['chambres'] ?> chambre(s)</span>
                                    <?php endif; ?>
                                    <?php if ($bien['salles_eau']): ?>
                                        <span><i class="fas fa-bath"></i> <?= $bien['salles_eau'] ?> salle(s) d'eau</span>
                                    <?php endif; ?>
                                </div>
                                <a href="/bien/<?= $bien['id'] ?>" class="read-more">
                                    Voir le bien <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="no-results" class="no-results" style="display: none;">
                    <p>Aucun bien ne correspond à votre recherche.</p>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page_courante > 1): ?>
                            <a href="?page=<?= $page_courante - 1 ?>" class="page-btn">
                                <i class="fas fa-chevron-left"></i> Précédent
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-btn <?= $i === $page_courante ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page_courante < $total_pages): ?>
                            <a href="?page=<?= $page_courante + 1 ?>" class="page-btn">
                                Suivant <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

<?php include __DIR__ . '/../include/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const propertyCards = document.querySelectorAll('.property-card');
    const noResults = document.getElementById('no-results');
    const pagination = document.querySelector('.pagination');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Retirer la classe active de tous les boutons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            btn.classList.add('active');

            const type = btn.dataset.type;
            let visibleCount = 0;

            propertyCards.forEach(card => {
                if (type === 'all' || card.dataset.type === type) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                    visibleCount++;
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });

            // Afficher ou masquer le message "aucun résultat"
            if (visibleCount === 0) {
                noResults.style.display = 'block';
                if (pagination) pagination.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                if (pagination) pagination.style.display = 'flex';
            }
        });
    });
});
</script>

<style>
.no-results {
    text-align: center;
    padding: 2rem;
    background: var(--light-gray);
    border-radius: var(--border-radius);
    margin: 2rem 0;
}

.no-results p {
    color: var(--text-color);
    font-size: 1.1rem;
    margin: 0;
}
</style>
</body>
</html> 