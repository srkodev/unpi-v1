<?php

/**
 * Fonction d'aide pour inclure des fichiers de manière sécurisée
 */
function include_file($path) {
    $fullPath = __DIR__ . '/../../public/' . $path;
    if (file_exists($fullPath)) {
        include $fullPath;
    } else {
        error_log("Fichier non trouvé : $fullPath");
    }
}

/**
 * Fonction pour formater une date en français
 */
function formatDateFrench($date) {
    $mois = [
        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
        5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
    ];
    
    $timestamp = strtotime($date);
    $jour = date('j', $timestamp);
    $mois_num = date('n', $timestamp);
    $annee = date('Y', $timestamp);
    
    return $jour . ' ' . $mois[$mois_num] . ' ' . $annee;
} 