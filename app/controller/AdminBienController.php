<?php
namespace App\Controller;

use App\Models\Bien;
use App\Models\BienImage;
use PDOException;

class AdminBienController
{
    private $adminController;

    public function __construct()
    {
        $this->adminController = new AdminController();
    }

    public function index(): void
    {
        try {
            error_log("Début de la récupération de la liste des biens");
            $biens = Bien::getAll();
            error_log("Nombre de biens trouvés: " . count($biens));
            error_log("Liste des biens: " . print_r($biens, true));
            
            // S'assurer que la variable est disponible dans la vue
            $GLOBALS['biens'] = $biens;
            
            include_once __DIR__ . '/../../public/admin/biens/liste_biens.php';
        } catch (\Exception $e) {
            error_log("Erreur dans index: " . $e->getMessage());
            $GLOBALS['biens'] = [];
            include_once __DIR__ . '/../../public/admin/biens/liste_biens.php';
        }
    }

    public function create(): void
    {
        error_log("Méthode create() appelée");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("Début de la création d'un bien");
                
                // Vérification des données
                if (empty($_POST['titre'])) {
                    throw new \Exception('Le titre est requis');
                }

                if (empty($_POST['type'])) {
                    throw new \Exception('Le type est requis');
                }

                $data = [
                    'titre' => $_POST['titre'],
                    'type' => $_POST['type'],
                    'adresse' => $_POST['adresse'] ?? '',
                    'surface_m2' => $_POST['surface_m2'] ? (int)$_POST['surface_m2'] : null,
                    'chambres' => $_POST['chambres'] ? (int)$_POST['chambres'] : null,
                    'salles_eau' => $_POST['salles_eau'] ? (int)$_POST['salles_eau'] : null,
                    'prix' => $_POST['prix'] ? number_format((float)$_POST['prix'], 2, '.', '') : null,
                    'description' => $_POST['description'] ?? ''
                ];

                error_log("Données préparées pour l'insertion: " . print_r($data, true));

                // Création du bien
                $bienId = Bien::create($data);
                error_log("Bien créé avec l'ID: " . $bienId);

                if (!$bienId) {
                    throw new \Exception('Erreur lors de la création du bien');
                }

                // Gestion des images
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    error_log("Traitement des images: " . print_r($_FILES['images'], true));
                    $this->handleImages($bienId, $_FILES['images']);
                }

                error_log("Redirection vers la liste des biens");
                header('Location: /index.php/admin/biens/liste_biens');
                exit;
            } catch (\Exception $e) {
                error_log("Erreur dans create: " . $e->getMessage());
                $error = 'Une erreur est survenue lors de la création du bien: ' . $e->getMessage();
                include_once __DIR__ . '/../../public/admin/biens/form.php';
            }
        } else {
            error_log("Affichage du formulaire de création");
            include_once __DIR__ . '/../../public/admin/biens/form.php';
        }
    }

    public function edit(int $id): void
    {
        try {
            $bien = Bien::getById($id);
            if (!$bien) {
                header('Location: /index.php/admin/biens/liste_biens');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'titre' => $_POST['titre'],
                    'type' => $_POST['type'],
                    'adresse' => $_POST['adresse'] ?? '',
                    'surface_m2' => $_POST['surface_m2'] ? (int)$_POST['surface_m2'] : null,
                    'chambres' => $_POST['chambres'] ? (int)$_POST['chambres'] : null,
                    'salles_eau' => $_POST['salles_eau'] ? (int)$_POST['salles_eau'] : null,
                    'prix' => $_POST['prix'] ? number_format((float)$_POST['prix'], 2, '.', '') : null,
                    'description' => $_POST['description'] ?? ''
                ];

                Bien::update($id, $data);

                // Gestion des images
                if (isset($_FILES['images'])) {
                    $this->handleImages($id, $_FILES['images']);
                }

                header('Location: /index.php/admin/biens/liste_biens');
                exit;
            }

            $images = BienImage::listByBien($id);
            include_once __DIR__ . '/../../public/admin/biens/form.php';
        } catch (\Exception $e) {
            $error = 'Une erreur est survenue lors de la modification du bien';
            include_once __DIR__ . '/../../public/admin/biens/form.php';
        }
    }

    public function delete(int $id): void
    {
        try {
            Bien::delete($id);
            header('Location: /index.php/admin/biens/liste_biens');
            exit;
        } catch (\Exception $e) {
            $error = 'Une erreur est survenue lors de la suppression du bien';
            include_once __DIR__ . '/../../public/admin/biens/liste_biens.php';
        }
    }

    private function handleImages(int $bienId, array $files): void
    {
        try {
            $uploadDir = PUBLIC_PATH . 'uploads/biens/';
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
                        $imageId = BienImage::add(
                            $bienId,
                            'uploads/biens/' . $fileName,
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

    public function setPrimaryImage(int $bienId, int $imageId): void
    {
        try {
            BienImage::setPrimary($bienId, $imageId);
            $_SESSION['success'] = "L'image principale a été mise à jour avec succès.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour de l'image principale.";
            error_log("Erreur setPrimaryImage: " . $e->getMessage());
        }
        
        header("Location: /index.php/admin/biens/edit/" . $bienId);
        exit;
    }
} 