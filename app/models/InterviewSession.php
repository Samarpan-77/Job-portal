<?php
require_once BASE_PATH . '/app/config/database.php';

class InterviewSession
{

    private static function db()
    {
        return Database::connect();
    }

    public static function create($user_id, $job_role)
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT INTO interview_sessions (user_id,job_role)
            VALUES (?,?)
        ");
        $stmt->execute([$user_id, $job_role]);
        return $db->lastInsertId();
    }

    public static function updateScore($session_id, $score)
    {
        $db = self::db();
        $stmt = $db->prepare("
            UPDATE interview_sessions 
            SET total_score = total_score + ?
            WHERE id=?
        ");
        return $stmt->execute([$score, $session_id]);
    }

    public static function complete($session_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            UPDATE interview_sessions 
            SET status='completed'
            WHERE id=?
        ");
        return $stmt->execute([$session_id]);
    }

    public static function getByUser($user_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT * FROM interview_sessions 
            WHERE user_id=?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
