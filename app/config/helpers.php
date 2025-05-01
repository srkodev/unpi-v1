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