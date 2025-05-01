<?php
namespace App\Controller;

use App\Models\Actualite;
use App\Models\ActualiteImage;
use PDOException;

class AdminActualiteController
{
    private $adminController;

    public function __construct()
    {
        $this->adminController = new AdminController();
    }

    public function index(): void
    {
        try {
            $actualites = Actualite::getAll();
            include_once __DIR__ . '/../../public/admin/actualites/liste_actualites.php';
        } catch (\Exception $e) {
            error_log("Erreur dans index: " . $e->getMessage());
            $actualites = [];
            include_once __DIR__ . '/../../public/admin/actualites/liste_actualites.php';
        }
    }

    public function create(): void
    {
        error_log("Méthode create() appelée");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("Début de la création d'une actualité");
                
                // Vérification des données
                if (empty($_POST['titre'])) {
                    throw new \Exception('Le titre est requis');
                }

                if (empty($_POST['categorie'])) {
                    throw new \Exception('La catégorie est requise');
                }

                $data = [
                    'titre' => $_POST['titre'],
                    'slug' => $this->createSlug($_POST['titre']),
                    'categorie' => $_POST['categorie'],
                    'extrait' => $_POST['extrait'] ?? '',
                    'contenu' => $_POST['contenu'] ?? '',
                    'publie_le' => $_POST['publie_le'] ?? date('Y-m-d')
                ];

                error_log("Données préparées pour l'insertion: " . print_r($data, true));

                // Création de l'actualité
                $actualiteId = Actualite::create($data);
                error_log("Actualité créée avec l'ID: " . $actualiteId);

                if (!$actualiteId) {
                    throw new \Exception('Erreur lors de la création de l\'actualité');
                }

                // Gestion des images
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    error_log("Traitement des images: " . print_r($_FILES['images'], true));
                    $this->handleImages($actualiteId, $_FILES['images']);
                }

                error_log("Redirection vers la liste des actualités");
                header('Location: /index.php/admin/actualites/liste_actualites');
                exit;
            } catch (\Exception $e) {
                error_log("Erreur dans create: " . $e->getMessage());
                $error = 'Une erreur est survenue lors de la création de l\'actualité: ' . $e->getMessage();
                include_once __DIR__ . '/../../public/admin/actualites/form.php';
            }
        } else {
            error_log("Affichage du formulaire de création");
            include_once __DIR__ . '/../../public/admin/actualites/form.php';
        }
    }

    public function edit(int $id): void
    {
        try {
            $actualite = Actualite::getById($id);
            if (!$actualite) {
                header('Location: /index.php/admin/actualites/liste_actualites');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'titre' => $_POST['titre'] ?? '',
                    'slug' => $this->createSlug($_POST['titre']),
                    'categorie' => $_POST['categorie'] ?? '',
                    'extrait' => $_POST['extrait'] ?? '',
                    'contenu' => $_POST['contenu'] ?? '',
                    'publie_le' => $_POST['publie_le'] ?? date('Y-m-d')
                ];

                Actualite::update($id, $data);

                // Gestion des images
                if (isset($_FILES['images'])) {
                    $this->handleImages($id, $_FILES['images']);
                }

                header('Location: /index.php/admin/actualites/liste_actualites');
                exit;
            }

            $images = ActualiteImage::listByActualite($id);
            include_once __DIR__ . '/../../public/admin/actualites/form.php';
        } catch (\Exception $e) {
            $error = 'Une erreur est survenue lors de la modification de l\'actualité';
            include_once __DIR__ . '/../../public/admin/actualites/form.php';
        }
    }

    public function delete(int $id): void
    {
        try {
            Actualite::delete($id);
            header('Location: /index.php/admin/actualites/liste_actualites');
            exit;
        } catch (\Exception $e) {
            $error = 'Une erreur est survenue lors de la suppression de l\'actualité';
            include_once __DIR__ . '/../../public/admin/actualites/liste_actualites.php';
        }
    }

    public function setPrimaryImage(int $actualiteId, int $imageId): void
    {
        try {
            ActualiteImage::setPrimary($actualiteId, $imageId);
            $_SESSION['success'] = "L'image principale a été mise à jour avec succès.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour de l'image principale.";
            error_log("Erreur setPrimaryImage: " . $e->getMessage());
        }
        
        header("Location: /index.php/admin/actualites/edit/" . $actualiteId);
        exit;
    }

    private function createSlug(string $title): string
    {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    private function handleImages(int $actualiteId, array $files): void
    {
        try {
            $uploadDir = PUBLIC_PATH . 'uploads/actualites/';
            if (!file_exists($uploadDir)) {
                error_log("Le répertoire d'upload n'existe pas. Il devrait être créé par Docker: " . $uploadDir);
                throw new \Exception('Le dossier d\'upload n\'existe pas');
            }

            if (!is_writable($uploadDir)) {
                error_log("Le répertoire d'upload n'est pas accessible en écriture: " . $uploadDir);
                throw new \Exception('Le dossier d\'upload n\'est pas accessible en écriture');
            }

            foreach ($files['tmp_name'] as $key => $tmp_name) {
                if ($files['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = uniqid() . '_' . basename($files['name'][$key]);
                    $uploadFile = $uploadDir . $fileName;

                    error_log("Tentative d'upload de l'image: " . $uploadFile);

                    if (move_uploaded_file($tmp_name, $uploadFile)) {
                        $isPrimary = isset($_POST['is_primary']) && $_POST['is_primary'] == $key;
                        $imageId = ActualiteImage::add(
                            $actualiteId,
                            'uploads/actualites/' . $fileName,
                            $isPrimary,
                            $key
                        );
                        error_log("Image uploadée avec succès. ID: " . $imageId);
                    } else {
                        error_log("Échec de l'upload de l'image: " . $uploadFile);
                        throw new \Exception('Erreur lors de l\'upload de l\'image');
                    }
                } else if ($files['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                    error_log("Erreur lors de l'upload: " . $files['error'][$key]);
                }
            }
        } catch (\Exception $e) {
            error_log("Erreur dans handleImages: " . $e->getMessage());
            throw $e;
        }
    }
} 