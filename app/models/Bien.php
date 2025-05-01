<?php
namespace App\Models;

class Bien extends BaseModel
{
    private const TABLE = 'biens';

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
            
            $result = self::deleteRow(self::TABLE, $id);
            
            error_log("Suppression " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Bien::delete: " . $e->getMessage());
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

    /** Retourne un bien par son id */
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
}
