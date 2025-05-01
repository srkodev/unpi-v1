<?php
namespace App\Models;

error_log("Chargement de la classe Partenaire");

class Partenaire extends BaseModel
{
    private const TABLE = 'partenaires';

    /* ---------- CRUD ---------- */

    public static function create(array $data): int
    {
        try {
            error_log("Début de la création d'un partenaire");
            error_log("Données reçues: " . print_r($data, true));
            
            $id = self::insert(self::TABLE, $data);
            
            error_log("Partenaire créé avec l'ID: " . $id);
            return $id;
        } catch (\Exception $e) {
            error_log("Erreur dans Partenaire::create: " . $e->getMessage());
            throw $e;
        }
    }

    public static function update(int $id, array $data): bool
    {
        try {
            error_log("Début de la mise à jour du partenaire ID: " . $id);
            error_log("Données reçues: " . print_r($data, true));
            
            $result = self::updateRow(self::TABLE, $id, $data);
            
            error_log("Mise à jour " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Partenaire::update: " . $e->getMessage());
            throw $e;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            error_log("Début de la suppression du partenaire ID: " . $id);
            
            $result = self::deleteRow(self::TABLE, $id);
            
            error_log("Suppression " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Partenaire::delete: " . $e->getMessage());
            throw $e;
        }
    }

    /* ---------- Lecture ---------- */

    /** Tous les partenaires, triés par nom */
    public static function getAll(): array
    {
        try {
            error_log("Récupération de tous les partenaires");
            
            $result = self::$db
                ->query("SELECT * FROM " . self::TABLE . " ORDER BY nom ASC")
                ->fetchAll();
            
            error_log("Nombre de partenaires trouvés: " . count($result));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Partenaire::getAll: " . $e->getMessage());
            throw $e;
        }
    }

    /** Un partenaire précis */
    public static function getById(int $id): ?array
    {
        try {
            error_log("Récupération du partenaire ID: " . $id);
            
            $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch() ?: null;
            
            error_log("Partenaire " . ($result ? "trouvé" : "non trouvé"));
            return $result;
        } catch (\Exception $e) {
            error_log("Erreur dans Partenaire::getById: " . $e->getMessage());
            throw $e;
        }
    }
}
