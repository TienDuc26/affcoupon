<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getActiveCategories($type = 'coupon') {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE is_active = 1 AND type = ? ORDER BY name ASC");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getAllCategories($type = 'coupon') {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE type = ? ORDER BY created_at DESC");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function slugExists($slug, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function add($name, $slug, $description, $isActive, $type = 'coupon', $parentId = null) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, slug, description, is_active, type, parent_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([
            $name, 
            $slug, 
            $description, 
            $isActive ? 1 : 0,
            $type,
            $parentId
        ]);
    }

    public function update($id, $name, $slug, $description, $isActive, $parentId = null) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, is_active = ?, parent_id = ? WHERE id = ?");
        return $stmt->execute([
            $name, 
            $slug, 
            $description, 
            $isActive ? 1 : 0, 
            $parentId,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

