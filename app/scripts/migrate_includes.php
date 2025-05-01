<?php

$viewDir = __DIR__ . '/../../public/view';
$files = glob($viewDir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Remplacer les anciens chemins d'inclusion
    $content = preg_replace(
        '/include\s*\(\s*[\'"]\.\.\/include\/([^\'"]+)[\'"]\s*\)/',
        'include_file(\'include/$1\')',
        $content
    );
    
    // Écrire le contenu modifié
    file_put_contents($file, $content);
    
    echo "Migré : " . basename($file) . "\n";
}

echo "Migration terminée !\n"; 