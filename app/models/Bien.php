<?php
namespace App\Models;

class Bien extends BaseModel
{
    private const TABLE = 'biens';
    private const IMAGES_TABLE = 'bien_images';

    /* ---------- CRUD ---------- */

    public static function create(array $data): int
    {
        try {
            error_log("Début de la création d'un bien dans le modèle");
            error_log("Données reçues: " . print_r($data, true));
            
            $id = self::insert(self::TABLE, $data);
            
            error_log("Bien créé avec l'ID: " . $id);
            return $id;
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::create: " . $e->getMessage());
            throw $e;
        }
    }

    public static function update(int $id, array $data): bool
    {
        try {
            error_log("Début de la mise à jour du bien ID: " . $id);
            error_log("Données reçues: " . print_r($data, true));
            
            $result = self::updateRow(self::TABLE, $id, $data);
            
            error_log("Mise à jour " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::update: " . $e->getMessage());
            throw $e;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            error_log("Début de la suppression du bien ID: " . $id);
            
            // Supprimer d'abord les images associées
            $images = BienImage::listByBien($id);
            error_log("Images trouvées pour le bien ID $id: " . count($images));
            
            foreach ($images as $image) {
                // Supprimer le fichier physique si il existe
                $imagePath = PUBLIC_PATH . $image['url'];
                error_log("Tentative de suppression du fichier: " . $imagePath);
                
                if (file_exists($imagePath)) {
                    if (unlink($imagePath)) {
                        error_log("Fichier supprimé avec succès: " . $imagePath);
                    } else {
                        error_log("Échec de la suppression du fichier: " . $imagePath);
                    }
                } else {
                    error_log("Fichier non trouvé: " . $imagePath);
                }
                
                // Supprimer l'enregistrement de la base de données
                if (BienImage::delete($image['id'])) {
                    error_log("Enregistrement de l'image supprimé avec succès. ID: " . $image['id']);
                } else {
                    error_log("Échec de la suppression de l'enregistrement de l'image. ID: " . $image['id']);
                }
            }
            
            // Puis supprimer le bien
            error_log("Tentative de suppression du bien ID: " . $id);
            $result = self::deleteRow(self::TABLE, $id);
            
            if ($result) {
                error_log("Bien supprimé avec succès. ID: " . $id);
            } else {
                error_log("Échec de la suppression du bien. ID: " . $id);
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::delete: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    /* ---------- Lecture ---------- */

    /** Retourne l'ensemble des biens */
    public static function getAll(): array
    {
        try {
            error_log("Début de la récupération de tous les biens");
            $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
            error_log("Requête SQL exécutée: SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC");
            error_log("Résultat de la requête: " . print_r($result, true));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::getAll: " . $e->getMessage());
            return [];
        }
    }

    /** Retourne un bien par son ID */
    public static function getById(int $id): ?array
    {
        try {
            $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::getById: " . $e->getMessage());
            return null;
        }
    }

    /* ---------- Gestion des images ---------- */

    /** Récupère toutes les images d'un bien */
    public static function getImages(int $bienId): array
    {
        try {
            $stmt = self::$db->prepare("SELECT * FROM " . self::IMAGES_TABLE . " WHERE bien_id = ? ORDER BY is_primary DESC, position ASC");
            $stmt->execute([$bienId]);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::getImages: " . $e->getMessage());
            return [];
        }
    }

    /** Ajoute une image à un bien */
    public static function addImage(int $bienId, string $imageUrl, bool $isPrimary = false): bool
    {
        try {
            // Si c'est une image principale, désactiver les autres images principales
            if ($isPrimary) {
                $stmt = self::$db->prepare("UPDATE " . self::IMAGES_TABLE . " SET is_primary = 0 WHERE bien_id = ?");
                $stmt->execute([$bienId]);
            }

            // Récupérer la dernière position
            $stmt = self::$db->prepare("SELECT MAX(position) as max_pos FROM " . self::IMAGES_TABLE . " WHERE bien_id = ?");
            $stmt->execute([$bienId]);
            $result = $stmt->fetch();
            $position = ($result['max_pos'] ?? 0) + 1;

            // Ajouter la nouvelle image
            $stmt = self::$db->prepare("INSERT INTO " . self::IMAGES_TABLE . " (bien_id, url, is_primary, position) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$bienId, $imageUrl, $isPrimary ? 1 : 0, $position]);
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::addImage: " . $e->getMessage());
            throw $e;
        }
    }

    /** Supprime une image */
    public static function deleteImage(int $imageId): bool
    {
        try {
            $stmt = self::$db->prepare("DELETE FROM " . self::IMAGES_TABLE . " WHERE id = ?");
            return $stmt->execute([$imageId]);
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::deleteImage: " . $e->getMessage());
            throw $e;
        }
    }

    /** Supprime toutes les images d'un bien */
    public static function deleteImages(int $bienId): bool
    {
        try {
            $stmt = self::$db->prepare("DELETE FROM " . self::IMAGES_TABLE . " WHERE bien_id = ?");
            return $stmt->execute([$bienId]);
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::deleteImages: " . $e->getMessage());
            throw $e;
        }
    }

    /** Définit une image comme principale */
    public static function setPrimaryImage(int $bienId, int $imageId): bool
    {
        try {
            // Désactiver toutes les images principales
            $stmt = self::$db->prepare("UPDATE " . self::IMAGES_TABLE . " SET is_primary = 0 WHERE bien_id = ?");
            $stmt->execute([$bienId]);

            // Définir la nouvelle image principale
            $stmt = self::$db->prepare("UPDATE " . self::IMAGES_TABLE . " SET is_primary = 1 WHERE id = ? AND bien_id = ?");
            return $stmt->execute([$imageId, $bienId]);
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::setPrimaryImage: " . $e->getMessage());
            throw $e;
        }
    }
}
