<?php
require_once __DIR__ . '/../config/database.php';

class Media {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM media WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM media ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function add($url, $alt = null, $caption = null, $type = 'image') {
        $stmt = $this->db->prepare("
            INSERT INTO media (url, alt, caption, type, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $success = $stmt->execute([$url, $alt, $caption, $type]);
        return $success ? $this->db->lastInsertId() : false;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM media WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
