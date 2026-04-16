<?php
require_once BASE_PATH . '/app/config/database.php';

class Job
{

    private static function db()
    {
        return Database::connect();
    }
    public static function all()
    {
        return self::getAll();
    }

    public static function create($title, $description, $salary, $location, $employer_id, $imagePath = null)
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT INTO jobs (title,description,salary,location,employer_id,image_path)
            VALUES (?,?,?,?,?,?)
        ");
        $stmt->execute([$title, $description, $salary, $location, $employer_id, $imagePath]);
        return (int)$db->lastInsertId();
    }

    public static function getAll()
    {
        $db = self::db();
        return $db->query("
            SELECT jobs.*, users.name AS employer, users.company_name,
                   COALESCE(NULLIF(users.company_name, ''), users.name) AS employer_display_name
            FROM jobs
            JOIN users ON jobs.employer_id = users.id
            ORDER BY created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT jobs.*, users.name AS employer, users.company_name, users.company_logo,
                   users.company_description, users.company_location, users.company_website,
                   COALESCE(NULLIF(users.company_name, ''), users.name) AS employer_display_name
            FROM jobs
            JOIN users ON jobs.employer_id = users.id
            WHERE jobs.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM jobs WHERE id=?");
        return $stmt->execute([$id]);
    }

    public static function updateImagePath($id, $imagePath)
    {
        $db = self::db();
        $stmt = $db->prepare("UPDATE jobs SET image_path = ? WHERE id = ?");
        return $stmt->execute([$imagePath, $id]);
    }

    public static function getByEmployer($employer_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT jobs.*, users.name AS employer, users.company_name,
                   COALESCE(NULLIF(users.company_name, ''), users.name) AS employer_display_name
            FROM jobs
            JOIN users ON jobs.employer_id = users.id
            WHERE employer_id=?
        ");
        $stmt->execute([$employer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
