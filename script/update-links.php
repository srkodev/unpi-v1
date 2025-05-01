<?php
/**
 * Script pour remplacer tous les liens "/" par "/"
 * À exécuter à la racine du projet
 */

// Configuration
$rootDir = __DIR__ . '/..';
$pattern = '/\/unpi\/public\//';
$replacement = '/';
$extensions = ['php', 'html', 'css', 'js', 'htaccess'];

// Compteurs pour le rapport
$filesScanned = 0;
$filesModified = 0;
$replacementsCount = 0;

// Fonction récursive pour parcourir les répertoires
function processDirectory($dir, $pattern, $replacement, $extensions, &$filesScanned, &$filesModified, &$replacementsCount) {
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            // Ne pas traiter le répertoire "vendor" s'il existe
            if ($file !== 'vendor' && $file !== 'node_modules') {
                processDirectory($path, $pattern, $replacement, $extensions, $filesScanned, $filesModified, $replacementsCount);
            }
        } else {
            // Vérifier l'extension du fichier
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($extension, $extensions) || $file === '.htaccess') {
                $filesScanned++;
                $content = file_get_contents($path);
                $newContent = preg_replace($pattern, $replacement, $content, -1, $count);
                
                if ($count > 0) {
                    file_put_contents($path, $newContent);
                    $filesModified++;
                    $replacementsCount += $count;
                    echo "Modifié: $path ($count remplacements)\n";
                }
            }
        }
    }
}

// Traitement spécial pour .htaccess
$htaccessPath = $rootDir . '/public/.htaccess';
if (file_exists($htaccessPath)) {
    $content = file_get_contents($htaccessPath);
    $newContent = str_replace("RewriteBase /", "RewriteBase /", $content);
    file_put_contents($htaccessPath, $newContent);
    echo "Fichier .htaccess mis à jour.\n";
}

// Exécuter le traitement
echo "Début du traitement...\n";
processDirectory($rootDir, $pattern, $replacement, $extensions, $filesScanned, $filesModified, $replacementsCount);

// Afficher le rapport
echo "\n=== Rapport ===\n";
echo "Fichiers analysés: $filesScanned\n";
echo "Fichiers modifiés: $filesModified\n";
echo "Remplacements effectués: $replacementsCount\n";
echo "Terminé!\n"; 