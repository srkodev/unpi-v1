<?php
namespace App\Models;

class Actualite extends BaseModel
{
    private const TABLE = 'actualites';

    /* ---------- CRUD ---------- */

    public static function create(array $data): int
    {
        try {
            error_log("Début de la création d'une actualité dans le modèle");
            error_log("Données reçues: " . print_r($data, true));
            
            $id = self::insert(self::TABLE, $data);
            
            error_log("Actualité créée avec l'ID: " . $id);
            return $id;
        } catch (\Exception $e) {
            error_log("Erreur dans Actualite::create: " . $e->getMessage());
            throw $e;
        }
    }

    public static function update(int $id, array $data): bool
    {
        try {
            error_log("Début de la mise à jour de l'actualité ID: " . $id);
            error_log("Données reçues: " . print_r($data, true));
            
            $result = self::updateRow(self::TABLE, $id, $data);
            
            error_log("Mise à jour " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Actualite::update: " . $e->getMessage());
            throw $e;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            error_log("Début de la suppression de l'actualité ID: " . $id);
            
            $result = self::deleteRow(self::TABLE, $id);
            
            error_log("Suppression " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Actualite::delete: " . $e->getMessage());
            throw $e;
        }
    }

    /* ---------- Lecture ---------- */

    /** Toutes les actus, triées par date de publication */
    public static function getAll(): array
    {
        try {
            error_log("Récupération de toutes les actualités");
            
            $result = self::$db
                ->query("SELECT * FROM " . self::TABLE . " ORDER BY publie_le DESC")
                ->fetchAll();
            
            error_log("Nombre d'actualités trouvées: " . count($result));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Actualite::getAll: " . $e->getMessage());
            throw $e;
        }
    }

    /** Une actu précise */
    public static function getById(int $id): ?array
    {
        try {
            error_log("Récupération de l'actualité ID: " . $id);
            
            $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch() ?: null;
            
            error_log("Actualité " . ($result ? "trouvée" : "non trouvée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Actualite::getById: " . $e->getMessage());
            throw $e;
        }
    }
}
