<?php include __DIR__ . '/../include/header.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Mentions Légales</h1>
                </div>
            </div>
        </section>

        <section class="legal-section">
            <div class="legal-container">
                <h2>Informations légales</h2>
                
                <h3>Éditeur du site</h3>
                <p>Chambre Syndicale des Propriétaires Immobiliers de l'Aube (CSPI10)</p>
                <p>Association loi 1901</p>
                <p>20 Rue Général de Gaulle, 10000 Troyes</p>
                <p>Téléphone : 03 25 73 01 19</p>
                <p>Email : chambredesproprietaires10@gmail.com</p>

                <h3>Directeur de la publication</h3>
                <p>Denis LAPÔTRE, Président de la CSPI10</p>

                <h3>Hébergement</h3>
                <p>Jules Crevoisier</p>
                <p>21 bis, rue de Beauregard, Bâtiment D</p>
                <p>Téléphone : +33 7 87 35 96 48</p>

                <h3>Propriété intellectuelle</h3>
                <p>L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.</p>

                <h3>Protection des données personnelles</h3>
                <p>Conformément à la loi "Informatique et Libertés" du 6 janvier 1978 modifiée, vous disposez d'un droit d'accès, de rectification et de suppression des données vous concernant. Pour exercer ce droit, contactez-nous à l'adresse email mentionnée ci-dessus.</p>

                <h3>Cookies</h3>
                <p>Ce site utilise des cookies pour améliorer votre expérience de navigation. Vous pouvez les désactiver à tout moment dans les paramètres de votre navigateur.</p>

                <h3>Liens hypertextes</h3>
                <p>La création de liens hypertextes vers le présent site est soumise à l'accord préalable du directeur de la publication.</p>

                <h3>Crédits</h3>
                <p>Conception et réalisation : Vosoft</p>
                <p>Images : Picsum Photos</p>
                <p>Icônes : Font Awesome</p>
            </div>
        </section>

        <a href="#" class="back-to-top" id="backToTop">
            <i class="fas fa-arrow-up"></i>
        </a>
    </main>

<?php include __DIR__ . '/../include/footer.php'; ?>

    <script>
        // Bouton retour en haut
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html> 