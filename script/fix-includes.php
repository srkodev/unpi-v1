<?php
/**
 * Script pour corriger les problèmes d'inclusion de fichiers
 * Spécifiquement pour les chemins qui utilisent DOCUMENT_ROOT + /unpi/public
 */

// Configuration
$rootDir = __DIR__ . '/..';
$extensions = ['php'];
$patterns = [
    // Motif pour les inclusions qui utilisent $_SERVER['DOCUMENT_ROOT']
    '/\$_SERVER\s*\[\s*[\'"]DOCUMENT_ROOT[\'"]\s*\]\s*\.\s*[\'"]\/unpi[\'"]/',
    // Motif pour les inclusions qui utilisent $root . '/public/include/
    '/(\$root\s*\.\s*[\'"])\/public\/include\/([\'"])/i',
    // Motif pour les inclusions directes avec chemin /unpi/public/
    '/include\s*\(\s*[\'"]\/unpi\/public\//'
];
$replacements = [
    // Remplacer par $_SERVER['DOCUMENT_ROOT']
    '$_SERVER[\'DOCUMENT_ROOT\']',
    // Remplacer par $root . '/include/
    '$1/include/$2',
    // Remplacer par include(/'
    'include(\''
];

// Compteurs pour le rapport
$filesScanned = 0;
$filesModified = 0;
$replacementsCount = 0;

// Fonction récursive pour parcourir les répertoires
function processDirectory($dir, $patterns, $replacements, $extensions, &$filesScanned, &$filesModified, &$replacementsCount) {
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            // Ne pas traiter le répertoire "vendor" s'il existe
            if ($file !== 'vendor' && $file !== 'node_modules') {
                processDirectory($path, $patterns, $replacements, $extensions, $filesScanned, $filesModified, $replacementsCount);
            }
        } else {
            // Vérifier l'extension du fichier
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($extension, $extensions)) {
                $filesScanned++;
                $content = file_get_contents($path);
                $modified = false;
                
                // Appliquer chaque motif et remplacement
                for ($i = 0; $i < count($patterns); $i++) {
                    $newContent = preg_replace($patterns[$i], $replacements[$i], $content, -1, $count);
                    if ($count > 0) {
                        $content = $newContent;
                        $replacementsCount += $count;
                        $modified = true;
                    }
                }
                
                // Rechercher spécifiquement le problème dans mentions-legales.php
                if (strpos($path, 'mentions-legales.php') !== false) {
                    $specificPattern = '/\$root\s*=\s*\$_SERVER\s*\[\s*[\'"]DOCUMENT_ROOT[\'"]\s*\]\s*\.\s*[\'"]\/unpi[\'"];/';
                    $specificReplacement = '$root = $_SERVER[\'DOCUMENT_ROOT\'];';
                    $newContent = preg_replace($specificPattern, $specificReplacement, $content, -1, $count);
                    if ($count > 0) {
                        $content = $newContent;
                        $replacementsCount += $count;
                        $modified = true;
                    }
                }
                
                if ($modified) {
                    file_put_contents($path, $content);
                    $filesModified++;
                    echo "Modifié: $path\n";
                }
            }
        }
    }
}

// Exécuter le traitement
echo "Début de la correction des includes...\n";
processDirectory($rootDir, $patterns, $replacements, $extensions, $filesScanned, $filesModified, $replacementsCount);

// Traitement spécifique pour mentions-legales.php
$mentionsPath = $rootDir . '/public/view/mentions-legales.php';
if (file_exists($mentionsPath)) {
    $content = file_get_contents($mentionsPath);
    // Remplacer l'inclusion du footer
    $pattern = '/\$root\s*=\s*\$_SERVER\s*\[\s*[\'"]DOCUMENT_ROOT[\'"]\s*\]\s*\.\s*[\'"]\/unpi[\'"];\s*include\s*\$root\s*\.\s*[\'"]\/public\/include\/footer\.php[\'"];/';
    $replacement = 'include __DIR__ . \'/../include/footer.php\';';
    $newContent = preg_replace($pattern, $replacement, $content, -1, $count);
    if ($count > 0) {
        file_put_contents($mentionsPath, $newContent);
        echo "Corrigé spécifiquement: $mentionsPath\n";
    }
}

// Afficher le rapport
echo "\n=== Rapport ===\n";
echo "Fichiers analysés: $filesScanned\n";
echo "Fichiers modifiés: $filesModified\n";
echo "Remplacements effectués: $replacementsCount\n";
echo "Terminé!\n"; 