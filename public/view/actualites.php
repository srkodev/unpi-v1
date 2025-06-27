<?php
use App\Models\ActualiteImage;
include __DIR__ . '/../include/header.php';

// Calculer le nombre total de pages
$total_actualites = count($actualites);
$actualites_par_page = 6;
$total_pages = ceil($total_actualites / $actualites_par_page);

// Récupérer la page courante
$page_courante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page_courante = max(1, min($page_courante, $total_pages));

// Calculer les actualités à afficher pour la page courante
$debut = ($page_courante - 1) * $actualites_par_page;
$actualites_page = array_slice($actualites, $debut, $actualites_par_page);
?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Actualités</h1>
                <p>Restez informé des dernières nouvelles et événements de la FDPCI</p>
            </div>
        </section>

        <section class="news-section">
            <div class="container">
                <div class="filters">
                    <button class="filter-btn active" data-category="all">Toutes les actualités</button>
                    <?php foreach ($categories as $category): ?>
                        <button class="filter-btn" data-category="<?= $category ?>"><?= ucfirst($category) ?></button>
                    <?php endforeach; ?>
                </div>

                <div class="news-grid">
                    <?php foreach ($actualites_page as $actualite): ?>
                        <article class="news-card" data-category="<?= $actualite['categorie'] ?>">
                            <div class="news-image">
                                <?php
                                $image = ActualiteImage::getPrimaryImage($actualite['id']);
                                if ($image) {
                                    $imageUrl = '/' . $image['url']; // Format: /uploads/actualites/filename.jpg
                                } else {
                                    $imageUrl = 'https://picsum.photos/800/600?random=' . $actualite['id'];
                                }
                                ?>
                                <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($actualite['titre']) ?>" loading="lazy">
                            </div>
                            <div class="news-content">
                                <div class="news-meta">
                                    <span class="news-category"><?= ucfirst($actualite['categorie']) ?></span>
                                    <span class="news-date"><?= formatDateFrench($actualite['publie_le']) ?></span>
                                </div>
                                <h3 class="news-title"><?= htmlspecialchars($actualite['titre']) ?></h3>
                                <p class="news-excerpt"><?= htmlspecialchars($actualite['extrait']) ?></p>
                                <a href="/index.php/actualite/<?= $actualite['id'] ?>" class="read-more">
                                    Lire la suite <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="no-results" class="no-results" style="display: none;">
                    <p>Aucune actualité ne correspond à votre recherche.</p>
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
    const newsCards = document.querySelectorAll('.news-card');
    const noResults = document.getElementById('no-results');
    const pagination = document.querySelector('.pagination');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Retirer la classe active de tous les boutons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            btn.classList.add('active');

            const category = btn.dataset.category;
            let visibleCount = 0;

            newsCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
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

/* Styles responsive copiés de biens.php */
@media (max-width: 1200px) {
    .news-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .filters {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .news-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .news-card .news-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .news-grid {
        gap: 1rem;
    }
    
    .pagination {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .page-btn {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
}
</style>
</body>
</html> 