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

    public static function create($title, $description, $salary, $location, $employer_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT INTO jobs (title,description,salary,location,employer_id)
            VALUES (?,?,?,?,?)
        ");
        return $stmt->execute([$title, $description, $salary, $location, $employer_id]);
    }

    public static function getAll()
    {
        $db = self::db();
        return $db->query("
            SELECT jobs.*, users.name AS employer
            FROM jobs
            JOIN users ON jobs.employer_id = users.id
            ORDER BY created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM jobs WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM jobs WHERE id=?");
        return $stmt->execute([$id]);
    }

    public static function getByEmployer($employer_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT * FROM jobs WHERE employer_id=?
        ");
        $stmt->execute([$employer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
