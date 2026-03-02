<?php
require_once BASE_PATH . '/app/config/database.php';

class Application
{

    private static function db()
    {
        return Database::connect();
    }

    public static function apply($job_id, $user_id, $resume_id)
    {
        $db = self::db();

        // Prevent duplicate
        $check = $db->prepare("
            SELECT id FROM applications 
            WHERE job_id=? AND user_id=?
        ");
        $check->execute([$job_id, $user_id]);

        if ($check->rowCount() > 0) {
            return false;
        }

        $stmt = $db->prepare("
            INSERT INTO applications (job_id,user_id,resume_id)
            VALUES (?,?,?)
        ");
        return $stmt->execute([$job_id, $user_id, $resume_id]);
    }

    public static function getByUser($user_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT applications.*, jobs.title, jobs.location
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            WHERE applications.user_id=?
            ORDER BY applied_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByEmployer($employer_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT applications.*, users.name, users.email
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            JOIN users ON applications.user_id = users.id
            WHERE jobs.employer_id=?
        ");
        $stmt->execute([$employer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($application_id, $status)
    {
        $db = self::db();
        $stmt = $db->prepare("
            UPDATE applications SET status=? WHERE id=?
        ");
        return $stmt->execute([$status, $application_id]);
    }
}
