<?php
require_once BASE_PATH . '/app/config/database.php';

class Notification
{
    private static function db()
    {
        return Database::connect();
    }

    private static function ensureTable()
    {
        static $ensured = false;
        if ($ensured) {
            return;
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(180) NOT NULL,
                message TEXT NOT NULL,
                context_type VARCHAR(50) DEFAULT NULL,
                context_id INT DEFAULT NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
        self::db()->exec($sql);
        $ensured = true;
    }

    public static function create($userId, $title, $message, $contextType = null, $contextId = null)
    {
        self::ensureTable();
        $stmt = self::db()->prepare("
            INSERT INTO notifications (user_id, title, message, context_type, context_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            (int)$userId,
            trim((string)$title),
            trim((string)$message),
            $contextType !== null ? trim((string)$contextType) : null,
            $contextId !== null ? (int)$contextId : null,
        ]);
    }

    public static function getByUser($userId, $limit = 50)
    {
        self::ensureTable();
        $limit = max(1, min(100, (int)$limit));
        $stmt = self::db()->prepare("
            SELECT id, title, message, context_type, context_id, is_read, created_at
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC, id DESC
            LIMIT {$limit}
        ");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function unreadCount($userId)
    {
        self::ensureTable();
        $stmt = self::db()->prepare("
            SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([(int)$userId]);
        return (int)$stmt->fetchColumn();
    }

    public static function markAllAsRead($userId)
    {
        self::ensureTable();
        $stmt = self::db()->prepare("
            UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0
        ");
        return $stmt->execute([(int)$userId]);
    }
}
