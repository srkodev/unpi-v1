<?php
namespace App\Models;

class BienImage extends BaseModel
{
    private const TABLE = 'bien_images';

    public static function add(int $bienId, string $url, bool $isPrimary = false, int $position = 0): int
    {
        return self::insert(self::TABLE, [
            'bien_id' => $bienId,
            'url'     => $url,
            'is_primary' => $isPrimary ? 1 : 0,
            'position'   => $position,
        ]);
    }

    public static function setPrimary(int $bienId, int $imageId): void
    {
        self::$db->prepare(
            "UPDATE " . self::TABLE . " SET is_primary = 0 WHERE bien_id = ?"
        )->execute([$bienId]);

        self::updateRow(self::TABLE, $imageId, ['is_primary' => 1]);
    }

    public static function delete(int $id): bool
    {
        return self::deleteRow(self::TABLE, $id);
    }

    public static function listByBien(int $bienId): array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE bien_id = ? ORDER BY position");
        $stmt->execute([$bienId]);
        return $stmt->fetchAll();
    }

    public static function getPrimaryImage(int $bienId): ?array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE bien_id = ? AND is_primary = 1 LIMIT 1");
        $stmt->execute([$bienId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function getById(int $id): ?array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
