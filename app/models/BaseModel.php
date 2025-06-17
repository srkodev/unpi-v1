<?php
namespace App\Models;

use PDO;
use PDOException;

abstract class BaseModel
{
    protected static PDO $db;

    // À appeler une seule fois dans un bootstrap (index.php, etc.)
    public static function init(string $host, string $dbName, string $user, string $pass): void
    {
        if (!isset(self::$db)) {
            try {
                $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
                self::$db = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                error_log("Connexion à la base de données établie avec succès");
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données: " . $e->getMessage());
                throw $e;
            }
        }
    }

    /* ---------- Helpers génériques ---------- */

    // $data = ['colonne' => valeur, ...]
    protected static function insert(string $table, array $data): int
    {
        try {
            $cols = implode(',', array_keys($data));
            $binds = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO $table ($cols) VALUES ($binds)";

            error_log("Requête SQL d'insertion: " . $sql);
            error_log("Données à insérer: " . print_r($data, true));

            $stmt = self::$db->prepare($sql);
            $stmt->execute($data);
            $lastId = (int) self::$db->lastInsertId();
            
            error_log("Insertion réussie. ID: " . $lastId);
            return $lastId;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion dans $table: " . $e->getMessage());
            throw $e;
        }
    }

    protected static function updateRow(string $table, int $id, array $data): bool
    {
        try {
            $pairs = [];
            foreach ($data as $col => $val) {
                $pairs[] = "$col = :$col";
            }
            $sql = "UPDATE $table SET " . implode(',', $pairs) . " WHERE id = :id";
            $data['id'] = $id;

            error_log("Requête SQL de mise à jour: " . $sql);
            error_log("Données à mettre à jour: " . print_r($data, true));

            $stmt = self::$db->prepare($sql);
            $result = $stmt->execute($data);
            
            error_log("Mise à jour " . ($result ? "réussie" : "échouée"));
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour dans $table: " . $e->getMessage());
            throw $e;
        }
    }

    protected static function deleteRow(string $table, int $id): bool
    {
        try {
            if (!isset(self::$db)) {
                error_log("Erreur: La connexion à la base de données n'est pas initialisée");
                return false;
            }

            $sql = "DELETE FROM $table WHERE id = ?";
            error_log("Requête SQL de suppression: " . $sql . " (ID: $id)");

            $stmt = self::$db->prepare($sql);
            if (!$stmt) {
                error_log("Erreur lors de la préparation de la requête: " . print_r(self::$db->errorInfo(), true));
                return false;
            }

            $result = $stmt->execute([$id]);
            if (!$result) {
                error_log("Erreur lors de l'exécution de la requête: " . print_r($stmt->errorInfo(), true));
                return false;
            }

            $rowCount = $stmt->rowCount();
            error_log("Nombre de lignes supprimées: " . $rowCount);
            
            return $rowCount > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression dans $table: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
}
