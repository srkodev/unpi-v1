<?php
/**
 * Script pour ajouter un utilisateur administrateur
 * À exécuter dans le conteneur Docker
 */

// Configuration de la connexion à la base de données
$host = 'db';  // Nom du service dans docker-compose.yml
$dbname = 'fdpci';
$username = 'fdpci_user';
$password = 'ChangeMe#2025';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données réussie.\n";
    
    // Vérifier si l'administrateur existe déjà
    $stmt = $pdo->prepare("SELECT id, email FROM administrateurs WHERE email = ?");
    $email = 'admin@fdcpi.fr';
    $stmt->execute([$email]);
    $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingAdmin) {
        echo "L'administrateur avec l'email $email existe déjà (ID: {$existingAdmin['id']}).\n";
        
        // Mettre à jour le mot de passe
        $password = 'admin';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $updateStmt = $pdo->prepare("UPDATE administrateurs SET password = ? WHERE email = ?");
        $updateStmt->execute([$hashedPassword, $email]);
        
        echo "Le mot de passe a été mis à jour.\n";
    } else {
        // Créer un nouvel administrateur
        $password = 'admin';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $insertStmt = $pdo->prepare("INSERT INTO administrateurs (email, password, created_at) VALUES (?, ?, NOW())");
        $insertStmt->execute([$email, $hashedPassword]);
        
        $newId = $pdo->lastInsertId();
        echo "Nouvel administrateur créé avec l'ID: $newId\n";
    }
    
    // Afficher les administrateurs existants
    $listStmt = $pdo->query("SELECT id, email, created_at FROM administrateurs");
    $admins = $listStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nListe des administrateurs:\n";
    foreach ($admins as $admin) {
        echo "ID: {$admin['id']}, Email: {$admin['email']}, Créé le: {$admin['created_at']}\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
}
?> 