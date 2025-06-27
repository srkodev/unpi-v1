<?php
spl_autoload_register(function ($class) {
    // Convertir le namespace en chemin de fichier
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    
    // Mapping spécifique pour correspondre à la structure des dossiers
    $path_mappings = [
        'Models\\' => 'models/',
        'Controller\\' => 'controller/',
        'Config\\' => 'config/'
    ];
    
    $file_path = $relative_class;
    foreach ($path_mappings as $namespace_part => $directory) {
        if (str_starts_with($file_path, $namespace_part)) {
            $file_path = $directory . substr($file_path, strlen($namespace_part));
            break;
        }
    }
    
    $file = $base_dir . str_replace('\\', '/', $file_path) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Inclure les fonctions helper
require_once __DIR__ . '/helpers.php'; 