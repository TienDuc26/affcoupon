<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function authenticate($username, $passwordHash) {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE username = ? AND password_hash = ? AND is_active = 1
        ");
        $stmt->execute([$username, $passwordHash]);
        return $stmt->fetch();
    }

    public function usernameOrEmailExists($username, $email, $excludeUserId = null) {
        if ($excludeUserId !== null) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM users 
                WHERE (username = ? OR email = ?) AND id != ?
            ");
            $stmt->execute([$username, $email, $excludeUserId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM users 
                WHERE username = ? OR email = ?
            ");
            $stmt->execute([$username, $email]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAllForAdmin() {
        $stmt = $this->db->query("
            SELECT u.*, r.name as role_name 
            FROM users u
            JOIN roles r ON u.role_id = r.id
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function add($username, $email, $passwordHash, $fullName, $roleId, $isActive) {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password_hash, fullname, role_id, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([
            $username,
            $email,
            $passwordHash,
            $fullName,
            $roleId,
            $isActive ? 1 : 0
        ]);
    }

    public function update($id, $username, $email, $fullName, $roleId, $isActive) {
        $stmt = $this->db->prepare("
            UPDATE users SET 
                username = ?, 
                email = ?, 
                fullname = ?, 
                role_id = ?, 
                is_active = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $username,
            $email,
            $fullName,
            $roleId,
            $isActive ? 1 : 0,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
