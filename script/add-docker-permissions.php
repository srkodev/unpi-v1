<?php
/**
 * Script pour corriger les problèmes de permissions dans Docker
 * Ce script doit être exécuté à l'intérieur du conteneur
 */

echo "Fixing Docker permissions for upload directories...\n\n";

// Répertoires à créer et à rendre accessibles en écriture
$directories = [
    '/var/www/html/public/uploads',
    '/var/www/html/public/uploads/partenaires',
    '/var/www/html/public/uploads/biens',
    '/var/www/html/public/uploads/actualites'
];

// Créer et modifier les permissions de chaque répertoire
foreach ($directories as $dir) {
    echo "Processing directory: $dir\n";
    
    // Créer le répertoire s'il n'existe pas
    if (!file_exists($dir)) {
        echo "  - Creating directory...\n";
        if (!@mkdir($dir, 0777, true)) {
            echo "  - ERROR: Could not create directory!\n";
            $error = error_get_last();
            echo "  - Error message: " . ($error ? $error['message'] : 'Unknown error') . "\n";
            continue;
        }
    } else {
        echo "  - Directory already exists\n";
    }
    
    // Modifier les permissions
    echo "  - Setting permissions to 777...\n";
    if (!@chmod($dir, 0777)) {
        echo "  - ERROR: Could not change permissions!\n";
        $error = error_get_last();
        echo "  - Error message: " . ($error ? $error['message'] : 'Unknown error') . "\n";
    }
    
    // Vérifier si le répertoire est accessible en écriture
    if (is_writable($dir)) {
        echo "  - SUCCESS: Directory is now writable\n";
    } else {
        echo "  - WARNING: Directory is still not writable\n";
    }
    
    echo "\n";
}

// Créer un fichier de test dans chaque répertoire pour vérifier que l'écriture fonctionne
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $testFile = $dir . '/test_permissions.txt';
        echo "Testing write access to $dir:\n";
        
        if (file_put_contents($testFile, 'Test write access - ' . date('Y-m-d H:i:s')) !== false) {
            echo "  - SUCCESS: Test file created\n";
            // Supprimer le fichier de test
            unlink($testFile);
            echo "  - Test file removed\n";
        } else {
            echo "  - ERROR: Could not create test file!\n";
        }
        
        echo "\n";
    }
}

echo "Permissions check completed.\n";

// Afficher les informations actuelles sur le système
echo "\nSystem information:\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "User running PHP: " . exec('whoami') . "\n";
echo "Current working directory: " . getcwd() . "\n";

echo "\nDirectory permissions:\n";
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = fileperms($dir);
        $permsOctal = substr(sprintf('%o', $perms), -4);
        echo "$dir: $permsOctal\n";
    }
}

echo "\nPermission fixing completed.\n"; 