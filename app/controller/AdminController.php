<?php
namespace App\Controller;

use PDO;
use PDOException;

class AdminController {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("Session dans AdminController: " . print_r($_SESSION, true));
    }

    public function login() {
        error_log("Tentative de connexion");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            error_log("Email reçu: " . $email);

            if ($email && $password) {
                try {
                    $stmt = $this->pdo->prepare("SELECT * FROM administrateurs WHERE email = ?");
                    $stmt->execute([$email]);
                    $admin = $stmt->fetch();

                    if ($admin && password_verify($password, $admin['password'])) {
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_email'] = $admin['email'];
                        error_log("Connexion réussie. Session créée: " . print_r($_SESSION, true));
                        header('Location: /index.php/admin/dashboard');
                        exit;
                    } else {
                        error_log("Échec de la connexion: identifiants incorrects");
                        return ['error' => 'Email ou mot de passe incorrect'];
                    }
                } catch (PDOException $e) {
                    error_log("Erreur de connexion: " . $e->getMessage());
                    return ['error' => 'Une erreur est survenue'];
                }
            }
            return ['error' => 'Veuillez remplir tous les champs'];
        }
        return null;
    }

    public function logout() {
        error_log("Déconnexion. Session avant: " . print_r($_SESSION, true));
        session_destroy();
        error_log("Session détruite");
        header('Location: /index.php/admin/login');
        exit;
    }

    public function isLoggedIn() {
        $isLogged = isset($_SESSION['admin_id']);
        error_log("Vérification de connexion: " . ($isLogged ? "connecté" : "non connecté"));
        error_log("Session actuelle: " . print_r($_SESSION, true));
        return $isLogged;
    }

    public function requireLogin() {
        error_log("Vérification de l'authentification requise");
        if (!$this->isLoggedIn()) {
            error_log("Utilisateur non connecté. Redirection vers la page de login");
            header('Location: /index.php/admin/login');
            exit;
        }
    }
} 