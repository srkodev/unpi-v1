<?php
/**
 * Script pour corriger spécifiquement le fichier mentions-legales.php
 */

$filePath = __DIR__ . '/../public/view/mentions-legales.php';

if (file_exists($filePath)) {
    echo "Correction du fichier mentions-legales.php...\n";
    
    // Lire le contenu du fichier
    $content = file_get_contents($filePath);
    
    // Remplacer l'inclusion du footer avec $_SERVER['DOCUMENT_ROOT'] par une inclusion relative
    $oldCode = <<<'EOD'
<?php 
$root = $_SERVER['DOCUMENT_ROOT'];
include $root . '/public/include/footer.php'; 
?>
EOD;
    
    $newCode = <<<'EOD'
<?php include __DIR__ . '/../include/footer.php'; ?>
EOD;
    
    $newContent = str_replace($oldCode, $newCode, $content);
    
    // Si ça n'a pas fonctionné, essayons un motif plus général
    if ($newContent === $content) {
        $pattern = '/\<\?php\s*\$root\s*=\s*\$_SERVER\s*\[\s*[\'"]DOCUMENT_ROOT[\'"]\s*\]\s*\.\s*[\'"]\/unpi[\'"];\s*include\s*\$root\s*\.\s*[\'"]\/public\/include\/footer\.php[\'"];\s*\?\>/s';
        $replacement = '<?php include __DIR__ . \'/../include/footer.php\'; ?>';
        $newContent = preg_replace($pattern, $replacement, $content);
    }
    
    // Sauvegarder les modifications
    if ($newContent !== $content) {
        file_put_contents($filePath, $newContent);
        echo "Le fichier a été corrigé avec succès!\n";
    } else {
        echo "Aucune modification n'a été nécessaire ou le motif n'a pas été trouvé.\n";
        
        // Remplacement manuel
        echo "Tentative de remplacement manuel...\n";
        $manualFind = '$root = $_SERVER[\'DOCUMENT_ROOT\'] . \'/unpi\';';
        $manualReplace = '// Chemin relatif au lieu du chemin absolu';
        $newContent = str_replace($manualFind, $manualReplace, $content);
        
        $manualFind2 = 'include $root . \'/public/include/footer.php\';';
        $manualReplace2 = 'include __DIR__ . \'/../include/footer.php\';';
        $newContent = str_replace($manualFind2, $manualReplace2, $newContent);
        
        if ($newContent !== $content) {
            file_put_contents($filePath, $newContent);
            echo "Le fichier a été corrigé manuellement avec succès!\n";
        } else {
            echo "Échec du remplacement manuel. Veuillez corriger le fichier manuellement.\n";
            // Afficher le contenu pour vérification
            echo "\nContenu actuel du fichier:\n";
            echo str_replace('<?php', '<?php /* ', $content);
            echo " */\n";
        }
    }
} else {
    echo "Le fichier mentions-legales.php n'existe pas à l'emplacement attendu:\n$filePath\n";
} 