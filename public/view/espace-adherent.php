<?php
require_once __DIR__ . '/../../app/config/espace-adherent.php';

// Vérifier si l'utilisateur est connecté
requireAdherentLogin();

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    logoutAdherent();
    header('Location: /espace-adherent-login');
    exit;
}

include __DIR__ . '/../include/header.php';
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1><i class="fas fa-users"></i> Espace Adhérent</h1>
            <p>Bienvenue dans votre espace privé FDPCI</p>
        </div>
    </section>

    <section class="adherent-content">
        <div class="container">
            <!-- Barre de navigation -->
            <div class="adherent-nav">
                <div class="nav-welcome">
                    <i class="fas fa-user-circle"></i>
                    <span>Connecté en tant qu'adhérent</span>
                </div>
                <a href="?logout=1" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
            </div>

            <!-- Contenu principal -->
            <div class="row">
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h4>Documents exclusifs</h4>
                        <p>Accédez aux modèles de contrats, guides juridiques et ressources réservées aux adhérents.</p>
                        <button class="btn btn-primary">Consulter</button>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4>Formations</h4>
                        <p>Inscrivez-vous aux formations exclusives et webinaires réservés aux membres.</p>
                        <button class="btn btn-primary">S'inscrire</button>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h4>Support privilégié</h4>
                        <p>Bénéficiez d'un accès prioritaire à notre service d'assistance juridique.</p>
                        <button class="btn btn-primary">Contacter</button>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="content-section">
                        <h3><i class="fas fa-bell"></i> Actualités membres</h3>
                        <ul class="news-list">
                            <li>Nouvelle réglementation DPE - Impact pour les propriétaires</li>
                            <li>Assemblée générale 2024 - Inscriptions ouvertes</li>
                            <li>Partenariat assurance - Tarifs préférentiels</li>
                            <li>Formation fiscalité - Places limitées</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="content-section">
                        <h3><i class="fas fa-calendar-alt"></i> Prochains événements</h3>
                        <div class="event-list">
                            <div class="event-item">
                                <strong>15 Mars 2024</strong><br>
                                Formation DPE et rénovation énergétique
                            </div>
                            <div class="event-item">
                                <strong>28 Mars 2024</strong><br>
                                Assemblée générale annuelle
                            </div>
                            <div class="event-item">
                                <strong>10 Avril 2024</strong><br>
                                Conférence fiscalité immobilière
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../include/footer.php'; ?>

<style>
.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0 60px;
    text-align: center;
}

.hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.adherent-content {
    padding: 40px 0;
}

.adherent-nav {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-welcome {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
}

.info-card {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    height: 100%;
    margin-bottom: 20px;
}

.info-icon {
    color: #007bff;
    font-size: 3rem;
    margin-bottom: 20px;
}

.info-card h4 {
    color: #333;
    margin-bottom: 15px;
}

.info-card p {
    color: #666;
    margin-bottom: 20px;
}

.content-section {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
}

.content-section h3 {
    color: #333;
    margin-bottom: 20px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

.news-list {
    list-style: none;
    padding: 0;
}

.news-list li {
    padding: 12px 0;
    border-bottom: 1px solid #eee;
    color: #666;
}

.news-list li:last-child {
    border-bottom: none;
}

.event-item {
    padding: 15px 0;
    border-bottom: 1px solid #eee;
    color: #666;
}

.event-item:last-child {
    border-bottom: none;
}

.event-item strong {
    color: #007bff;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 10px 20px;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
    transform: translateY(-1px);
}
</style> 