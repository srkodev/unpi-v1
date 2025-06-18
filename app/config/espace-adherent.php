<?php
/**
 * Configuration pour l'espace adhérent
 */

// Mot de passe pour accéder à l'espace adhérent
// À changer en production !
define('ESPACE_ADHERENT_PASSWORD', 'adherent2025');

// Durée de session en secondes (24 heures par défaut)
define('ESPACE_ADHERENT_SESSION_DURATION', 24 * 60 * 60);

/**
 * Vérifie si l'utilisateur est connecté à l'espace adhérent
 * @return bool
 */
function isAdherentLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $is_logged_in = isset($_SESSION['espace_adherent_access']) && $_SESSION['espace_adherent_access'] === true;
    
    // Vérifier si la session n'a pas expiré
    if ($is_logged_in && isset($_SESSION['espace_adherent_timestamp'])) {
        if ((time() - $_SESSION['espace_adherent_timestamp']) > ESPACE_ADHERENT_SESSION_DURATION) {
            // Session expirée
            unset($_SESSION['espace_adherent_access']);
            unset($_SESSION['espace_adherent_timestamp']);
            return false;
        }
    }
    
    return $is_logged_in;
}

/**
 * Connecte un utilisateur à l'espace adhérent
 */
function loginAdherent() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['espace_adherent_access'] = true;
    $_SESSION['espace_adherent_timestamp'] = time();
}

/**
 * Déconnecte un utilisateur de l'espace adhérent
 */
function logoutAdherent() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    unset($_SESSION['espace_adherent_access']);
    unset($_SESSION['espace_adherent_timestamp']);
}

/**
 * Force la redirection vers la page de connexion si pas connecté
 */
function requireAdherentLogin() {
    if (!isAdherentLoggedIn()) {
        header('Location: /espace-adherent-login');
        exit;
    }
}
?> 