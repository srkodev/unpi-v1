<?php
require_once __DIR__ . '/../../app/config/autoload.php';
require_once __DIR__ . '/../../app/config/config.php';

use App\Models\Actualite;
use App\Models\ActualiteImage;

// Récupérer l'ID de l'actualité depuis l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les détails de l'actualité
$actualite = Actualite::getById($id);

// Rediriger si l'actualité n'existe pas
if (!$actualite) {
    header('Location: /actualites');
    exit;
}

// Récupérer les images de l'actualité
$images = ActualiteImage::listByActualite($id);
?>
<?php include __DIR__ . '/../include/header.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1><?= htmlspecialchars($actualite['titre']) ?></h1>
                <div class="news-meta">
                    <span class="news-category"><?= ucfirst($actualite['categorie']) ?></span>
                    <span class="news-date white-date"><?= formatDateFrench($actualite['publie_le']) ?></span>
                </div>
            </div>
        </section>

        <section class="news-detail">
            <div class="container">
                <?php if (!empty($images)): ?>
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image): ?>
                                <div class="swiper-slide">
                                    <img src="/<?=htmlspecialchars($image['url']) ?>" alt="<?= htmlspecialchars($actualite['titre']) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                <?php endif; ?>

                <div class="news-content">
                    <?php if (!empty($actualite['extrait'])): ?>
                        <div class="news-excerpt">
                            <?= htmlspecialchars($actualite['extrait']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="news-body">
                        <?= $actualite['contenu'] ?>
                    </div>
                </div>

                <div class="news-actions">
                    <a href="/index.php/actualites" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour aux actualités
                    </a>
                </div>
            </div>
        </section>
    </main>

<?php include __DIR__ . '/../include/footer.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>

<style>
.news-detail {
    padding: 40px 0;
}

.swiper {
    width: 100%;
    height: 500px;
    margin-bottom: 30px;
}

.swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-content {
    max-width: 800px;
    margin: 0 auto;
}

.news-excerpt {
    font-size: 1.2em;
    color: #666;
    margin-bottom: 30px;
    font-style: italic;
}

.news-body {
    line-height: 1.8;
}

.news-actions {
    margin-top: 40px;
    text-align: center;
}

.news-meta {
    margin-top: 10px;
}

.news-category {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    margin-right: 10px;
}

.news-date {
    color: #666;
}

/* Style pour la date en blanc dans le hero */
.hero .news-date.white-date {
    color: white !important;
}

.swiper-button-next,
.swiper-button-prev {
    color: #fff;
    background: rgba(255, 255, 255, 0.2);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: rgba(255, 255, 255, 0.3);
}

.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 20px;
}

.swiper-pagination-bullet {
    background: #fff;
    opacity: 0.7;
}

.swiper-pagination-bullet-active {
    background: #007bff;
    opacity: 1;
}
</style> 
</html> 