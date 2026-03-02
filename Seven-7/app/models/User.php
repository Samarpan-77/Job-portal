<?php
// Ensure database configuration is loaded correctly
require_once BASE_PATH . '/app/config/database.php';

class User
{

    private static function db()
    {
        return Database::connect();
    }

    public static function create($name, $email, $password, $role = 'user')
    {
        $db = self::db();
        $stmt = $db->prepare("
            INSERT INTO users (name,email,password,role)
            VALUES (?,?,?,?)
        ");
        return $stmt->execute([$name, $email, $password, $role]);
    }

    public static function findByEmail($email)
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll()
    {
        $db = self::db();
        return $db->query("SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updatePassword($id, $hashedPassword)
    {
        $db = self::db();
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public static function delete($id)
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM users WHERE id=?");
        return $stmt->execute([$id]);
    }
}
