<?php
require_once BASE_PATH . '/app/config/database.php';

class InterviewMessage
{

    private static function db()
    {
        return Database::connect();
    }

    public static function create($session_id, $question, $answer, $feedback, $score)
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT INTO interview_messages
            (session_id,question,user_answer,ai_feedback,score)
            VALUES (?,?,?,?,?)
        ");
        return $stmt->execute([
            $session_id,
            $question,
            $answer,
            $feedback,
            $score
        ]);
    }

    public static function getBySession($session_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT * FROM interview_messages
            WHERE session_id=?
            ORDER BY created_at ASC
        ");
        $stmt->execute([$session_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
