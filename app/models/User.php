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
            INSERT INTO users (name,email,password,role,company_name)
            VALUES (?,?,?,?,?)
        ");
        $companyName = $role === 'employer' ? $name : null;
        return $stmt->execute([$name, $email, $password, $role, $companyName]);
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
        return $db->query("SELECT id,name,email,role,company_name,created_at FROM users ORDER BY created_at DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateProfile(int $id, array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare("
            UPDATE users
            SET name = ?,
                headline = ?,
                bio = ?,
                location = ?,
                website = ?,
                company_name = ?,
                company_description = ?,
                company_website = ?,
                company_location = ?,
                profile_image = ?,
                company_logo = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'] ?? null,
            $data['headline'] ?? null,
            $data['bio'] ?? null,
            $data['location'] ?? null,
            $data['website'] ?? null,
            $data['company_name'] ?? null,
            $data['company_description'] ?? null,
            $data['company_website'] ?? null,
            $data['company_location'] ?? null,
            $data['profile_image'] ?? null,
            $data['company_logo'] ?? null,
            $id,
        ]);
    }

    public static function getPublicProfileById(int $id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT id, name, email, role, headline, bio, location, website, profile_image,
                   company_name, company_description, company_website, company_location, company_logo, created_at
            FROM users
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getEmployerCompanyById(int $id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT id, name, email, role, company_name, company_description, company_website,
                   company_location, company_logo, created_at
            FROM users
            WHERE id = ? AND role = 'employer'
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
