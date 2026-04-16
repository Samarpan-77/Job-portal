<?php
require_once BASE_PATH . '/app/config/database.php';

class PasswordReset
{
    private static function db()
    {
        return Database::connect();
    }

    public static function create($userId, $tokenHash, $expiresAt)
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT INTO password_resets (user_id, token_hash, expires_at)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$userId, $tokenHash, $expiresAt]);
    }

    public static function findValidByToken($token)
    {
        $db = self::db();
        $tokenHash = hash('sha256', $token);
        $stmt = $db->prepare("
            SELECT id, user_id, expires_at, used_at
            FROM password_resets
            WHERE token_hash = ?
            LIMIT 1
        ");
        $stmt->execute([$tokenHash]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        if (!empty($row['used_at'])) {
            return false;
        }

        $expiresAtTs = strtotime((string)$row['expires_at']);
        if ($expiresAtTs === false || $expiresAtTs <= time()) {
            return false;
        }

        return [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
        ];
    }

    public static function markUsed($id)
    {
        $db = self::db();
        $stmt = $db->prepare("UPDATE password_resets SET used_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function deleteByUserId($userId)
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM password_resets WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}
