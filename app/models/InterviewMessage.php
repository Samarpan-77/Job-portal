<?php
require_once BASE_PATH . '/app/config/database.php';

class InterviewMessage
{
    private static $columns = null;

    private static function db()
    {
        return Database::connect();
    }

    private static function getColumns(): array
    {
        if (is_array(self::$columns)) {
            return self::$columns;
        }

        $db = self::db();
        $columns = [];
        foreach ($db->query('SHOW COLUMNS FROM interview_messages')->fetchAll(PDO::FETCH_ASSOC) as $column) {
            $field = (string)($column['Field'] ?? '');
            if ($field !== '') {
                $columns[$field] = true;
            }
        }

        self::$columns = $columns;
        return self::$columns;
    }

    public static function create($session_id, $question, $answer, $feedback, $score)
    {
        $db = self::db();
        $columns = self::getColumns();

        $answerColumn = isset($columns['answer']) ? 'answer' : 'user_answer';
        $feedbackColumn = isset($columns['feedback']) ? 'feedback' : 'ai_feedback';

        $stmt = $db->prepare("
            INSERT INTO interview_messages
            (session_id,question,{$answerColumn},{$feedbackColumn},score)
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
