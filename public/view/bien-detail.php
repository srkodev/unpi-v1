<?php include __DIR__ . '/../include/header.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1><?= htmlspecialchars($bien['titre']) ?></h1>
                <div class="property-meta">
                    <span class="property-type"><?= ucfirst($bien['type']) ?></span>
                    <?php if ($bien['prix']): ?>
                        <span class="property-price"><?= number_format($bien['prix'], 0, ',', ' ') ?> €</span>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="property-detail">
            <div class="container">
                <?php if (!empty($images)): ?>
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image): ?>
                                <div class="swiper-slide">
                                    <img src="<?= htmlspecialchars($image['url']) ?>" alt="<?= htmlspecialchars($bien['titre']) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                <?php endif; ?>

                <div class="property-content">
                    <div class="property-details">
                        <?php if ($bien['surface_m2']): ?>
                            <div class="detail-item">
                                <i class="fas fa-vector-square"></i>
                                <span><?= $bien['surface_m2'] ?> m²</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($bien['chambres']): ?>
                            <div class="detail-item">
                                <i class="fas fa-bed"></i>
                                <span><?= $bien['chambres'] ?> chambre(s)</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($bien['salles_eau']): ?>
                            <div class="detail-item">
                                <i class="fas fa-bath"></i>
                                <span><?= $bien['salles_eau'] ?> salle(s) d'eau</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($bien['adresse'])): ?>
                        <div class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($bien['adresse']) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($bien['description'])): ?>
                        <div class="property-description">
                            <h3>Description</h3>
                            <div class="description-content">
                                <?= strip_tags($bien['description'], '<br><p><strong><em><ul><li><ol>') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="property-actions">
                        <a href="/index.php/biens" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour aux biens
                        </a>
                        <a href="/index.php/contact" class="btn btn-primary">
                            <i class="fas fa-envelope"></i> Contacter
                        </a>
                    </div>
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
.property-detail {
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

.property-content {
    max-width: 800px;
    margin: 0 auto;
}

.property-details {
    display: flex;
    gap: 20px;
    margin: 20px 0;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
}

.property-location {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 20px 0;
    color: #666;
}

.property-description {
    margin: 30px 0;
}

.property-description h3 {
    margin-bottom: 15px;
    color: #333;
}

.description-content {
    line-height: 1.8;
    color: #666;
}

.description-content p {
    margin-bottom: 15px;
}

.property-actions {
    display: flex;
    gap: 15px;
    margin-top: 40px;
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