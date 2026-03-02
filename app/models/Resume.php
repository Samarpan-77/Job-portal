<?php
require_once BASE_PATH . '/app/config/database.php';

class Resume
{

    private static function db()
    {
        return Database::connect();
    }

    public static function create($user_id, $data)
    {
        $db = self::db();
        $jsonData = json_encode(self::normalizeData($data), JSON_UNESCAPED_UNICODE);

        $stmt = $db->prepare("
            INSERT INTO resumes (user_id,content_json)
            VALUES (?,?)
        ");
        return $stmt->execute([$user_id, $jsonData]);
    }

    public static function getByUser($user_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT * FROM resumes WHERE user_id=?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['parsed_content'] = self::decodeContent($row['content_json'] ?? '');
        }

        return $rows;
    }

    public static function find($id, $user_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            SELECT * FROM resumes 
            WHERE id=? AND user_id=?
        ");
        $stmt->execute([$id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $row['parsed_content'] = self::decodeContent($row['content_json'] ?? '');
        }
        return $row;
    }

    public static function delete($id, $user_id)
    {
        $db = self::db();
        $stmt = $db->prepare("
            DELETE FROM resumes 
            WHERE id=? AND user_id=?
        ");
        return $stmt->execute([$id, $user_id]);
    }

    public static function decodeContent($json)
    {
        $decoded = json_decode((string)$json, true);
        if (!is_array($decoded)) {
            $decoded = [];
        }

        return self::normalizeData($decoded);
    }

    public static function normalizeData($data)
    {
        $data = is_array($data) ? $data : [];

        $fullName = trim((string)($data['full_name'] ?? $data['name'] ?? ''));
        $headline = trim((string)($data['headline'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $phone = trim((string)($data['phone'] ?? ''));
        $address = trim((string)($data['address'] ?? ''));
        $summary = trim((string)($data['summary'] ?? ''));

        $skills = self::normalizeListField($data['skills'] ?? []);
        $education = self::normalizeListField($data['education_items'] ?? ($data['education'] ?? []));
        $experience = self::normalizeListField($data['experience_items'] ?? ($data['experience'] ?? []));
        $projects = self::normalizeListField($data['projects'] ?? []);
        $certifications = self::normalizeListField($data['certifications'] ?? []);

        return [
            'full_name' => $fullName,
            'headline' => $headline,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'summary' => $summary,
            'skills' => $skills,
            'education_items' => $education,
            'experience_items' => $experience,
            'projects' => $projects,
            'certifications' => $certifications,
        ];
    }

    private static function normalizeListField($value)
    {
        if (is_string($value)) {
            $parts = preg_split('/\r\n|\r|\n|,/', $value);
        } elseif (is_array($value)) {
            $parts = $value;
        } else {
            $parts = [];
        }

        $cleaned = [];
        foreach ($parts as $item) {
            $text = trim((string)$item);
            if ($text !== '') {
                $cleaned[] = $text;
            }
        }

        return array_values(array_unique($cleaned));
    }
}
