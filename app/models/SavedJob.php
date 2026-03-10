<?php
require_once BASE_PATH . '/app/config/database.php';

class SavedJob
{
    private static function db()
    {
        return Database::connect();
    }

    public static function save(int $userId, int $jobId): bool
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT IGNORE INTO saved_jobs (user_id, job_id)
            VALUES (?, ?)
        ");
        return $stmt->execute([$userId, $jobId]);
    }

    public static function remove(int $userId, int $jobId): bool
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
        return $stmt->execute([$userId, $jobId]);
    }

    public static function isSaved(int $userId, int $jobId): bool
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT 1 FROM saved_jobs WHERE user_id = ? AND job_id = ? LIMIT 1");
        $stmt->execute([$userId, $jobId]);
        return (bool)$stmt->fetchColumn();
    }

    public static function getSavedJobIds(int $userId): array
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT job_id FROM saved_jobs WHERE user_id = ?");
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $saved = [];
        foreach ($rows as $jobId) {
            $saved[(int)$jobId] = true;
        }
        return $saved;
    }

    public static function getByUser(int $userId): array
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT j.*, u.name AS employer, u.company_name,
                   COALESCE(NULLIF(u.company_name, ''), u.name) AS employer_display_name,
                   s.created_at AS saved_at
            FROM saved_jobs s
            JOIN jobs j ON s.job_id = j.id
            JOIN users u ON j.employer_id = u.id
            WHERE s.user_id = ?
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByUser(int $userId): int
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT COUNT(*) FROM saved_jobs WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
}
