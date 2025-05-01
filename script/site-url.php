<?php
/**
 * Définition de la constante d'URL du site pour l'environnement Docker
 * Ce fichier sera inclus dans l'application
 */

// URL de base du site (à modifier selon l'environnement)
if (!defined('SITE_URL')) {
    define('SITE_URL', '/');
} 