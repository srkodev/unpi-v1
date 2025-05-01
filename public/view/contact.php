<?php include_file('include/header.php'); ?>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Contactez-nous</h1>
                    <p>Nous sommes à votre écoute pour répondre à toutes vos questions</p>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-container">
                    <div class="contact-info">
                        <h3>Informations de contact</h3>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Adresse</h4>
                                <p>123 Rue Principale, 10000 Troyes</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h4>Téléphone</h4>
                                <p>03 25 XX XX XX</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h4>Email</h4>
                                <p>contact@fdpci-aube.fr</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Horaires</h4>
                                <p>Lundi - Vendredi: 9h00 - 17h00</p>
                                <p>Samedi: 9h00 - 12h00</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form">
                        <h3>Envoyez-nous un message</h3>
                        <form id="contactForm">
                            <div class="form-group">
                                <label for="name">Nom complet</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Sujet</label>
                                <input type="text" id="subject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer le message</button>
                        </form>
                    </div>
                </div>

                <div class="map-section">
                    <h3>Notre localisation</h3>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2645.1234567890123!2d4.123456789012345!3d48.12345678901234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDjCsDA3JzI0LjQiTiA0wrAwNyc0Mi4xIkU!5e0!3m2!1sfr!2sfr!4v1234567890123!5m2!1sfr!2sfr" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php include_file('include/footer.php'); ?>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Simulation d'envoi de formulaire
            alert('Votre message a été envoyé avec succès !');
            this.reset();
        });
    </script>
</rewritten_file> 