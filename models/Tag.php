<?php
require_once __DIR__ . '/../config/database.php';

class Tag {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM tags WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM tags ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getTagsByPostId($postId) {
        $stmt = $this->db->prepare("
            SELECT t.* 
            FROM tags t
            JOIN post_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = ?
            ORDER BY t.name ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    public function slugExists($slug, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tags WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tags WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function add($name, $slug) {
        $stmt = $this->db->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
        $success = $stmt->execute([$name, $slug]);
        return $success ? $this->db->lastInsertId() : false;
    }

    public function update($id, $name, $slug) {
        $stmt = $this->db->prepare("UPDATE tags SET name = ?, slug = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tags WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Finds a tag by name (case-insensitive). If it doesn't exist, it creates it.
     * Returns the tag ID.
     */
    public function getOrCreateTag($name) {
        $name = trim($name);
        if (empty($name)) return false;

        $stmt = $this->db->prepare("SELECT id FROM tags WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$name]);
        $tagId = $stmt->fetchColumn();

        if ($tagId) {
            return $tagId;
        }

        // Create new tag
        $slug = $this->generateSlug($name);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $this->add($name, $slug);
    }

    /**
     * Updates many-to-many mapping in post_tags table for a given post
     */
    public function updatePostTags($postId, array $tagNames) {
        // 1. Delete existing associations
        $stmt = $this->db->prepare("DELETE FROM post_tags WHERE post_id = ?");
        $stmt->execute([$postId]);

        // 2. Insert new associations
        $insertedTags = [];
        $assocStmt = $this->db->prepare("INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)");

        foreach ($tagNames as $name) {
            $name = trim($name);
            if (empty($name)) continue;

            $tagId = $this->getOrCreateTag($name);
            if ($tagId && !in_array($tagId, $insertedTags)) {
                $assocStmt->execute([$postId, $tagId]);
                $insertedTags[] = $tagId;
            }
        }
        return true;
    }

    private function generateSlug($text) {
        if (function_exists('mb_strtolower')) {
            $text = mb_strtolower($text, 'UTF-8');
        } else {
            $text = strtolower($text);
        }

        $replacements = [
            'á'=>'a','à'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a','ă'=>'a','ắ'=>'a','ằ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a','â'=>'a','ấ'=>'a','ầ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
            'é'=>'e','è'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e','ê'=>'e','ế'=>'e','ề'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
            'í'=>'i','ì'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
            'ó'=>'o','ò'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o','ô'=>'o','ố'=>'o','ồ'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o','ơ'=>'o','ớ'=>'o','ờ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
            'ú'=>'u','ù'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u','ư'=>'u','ứ'=>'u','ừ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
            'ý'=>'y','ỳ'=>'y','ỷ'=>'y','ỹ'=>'y','yt'=>'y','ỵ'=>'y',
            'đ'=>'d'
        ];

        $text = strtr($text, $replacements);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/\s+/', '-', $text);
        return trim($text, '-');
    }
}
