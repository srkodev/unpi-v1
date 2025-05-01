<?php
namespace App\Models;

class ActualiteImage extends BaseModel
{
    private const TABLE = 'actualite_images';

    public static function add(int $actualiteId, string $url, bool $isPrimary = false, int $position = 0): int
    {
        return self::insert(self::TABLE, [
            'actualite_id' => $actualiteId,
            'url'          => $url,
            'is_primary'   => $isPrimary ? 1 : 0,
            'position'     => $position,
        ]);
    }

    public static function setPrimary(int $actualiteId, int $imageId): void
    {
        self::$db->prepare(
            "UPDATE " . self::TABLE . " SET is_primary = 0 WHERE actualite_id = ?"
        )->execute([$actualiteId]);

        self::updateRow(self::TABLE, $imageId, ['is_primary' => 1]);
    }

    public static function delete(int $id): bool
    {
        return self::deleteRow(self::TABLE, $id);
    }

    public static function listByActualite(int $actualiteId): array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE actualite_id = ? ORDER BY position");
        $stmt->execute([$actualiteId]);
        return $stmt->fetchAll();
    }

    public static function getPrimaryImage(int $actualiteId): ?array
    {
        $stmt = self::$db->prepare("SELECT * FROM " . self::TABLE . " WHERE actualite_id = ? AND is_primary = 1 LIMIT 1");
        $stmt->execute([$actualiteId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
