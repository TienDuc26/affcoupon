<?php
require_once __DIR__ . '/../config/database.php';

class Coupon {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getActiveCoupons($categoryId = null, $page = 1, $pageSize = 8, $keyword = null) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT c.*, cat.name as category_name 
                FROM coupons c
                JOIN categories cat ON c.category_id = cat.id
                WHERE c.is_active = 1";
        
        $params = [];
        if ($categoryId !== null) {
            $sql .= " AND c.category_id = ?";
            $params[] = $categoryId;
        }

        if ($keyword !== null && $keyword !== '') {
            $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }

        $sql .= " ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
        
        // PDO needs offset and limit as integers or properly bound. In MySQL we can append them.
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters safely
        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }
        $stmt->bindValue($paramIndex++, (int)$pageSize, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countActiveCoupons($categoryId = null, $keyword = null) {
        $sql = "SELECT COUNT(*) FROM coupons WHERE is_active = 1";
        $params = [];
        
        if ($categoryId !== null) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }

        if ($keyword !== null && $keyword !== '') {
            $sql .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByIdWithDetails($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.name as category_name 
            FROM coupons c
            JOIN categories cat ON c.category_id = cat.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getBySlugWithDetails($slug) {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.name as category_name 
            FROM coupons c
            JOIN categories cat ON c.category_id = cat.id
            WHERE c.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getAllForAdmin($keyword = null, $categoryId = null, $isActive = null) {
        $sql = "SELECT c.*, cat.name as category_name 
                FROM coupons c
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE 1=1";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND c.title LIKE ?";
            $params[] = "%" . $keyword . "%";
        }

        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND c.category_id = ?";
            $params[] = $categoryId;
        }

        if ($isActive !== null && $isActive !== '') {
            $sql .= " AND c.is_active = ?";
            $params[] = $isActive ? 1 : 0;
        }

        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function add($title, $slug, $description, $content, $thumbnailUrl, $affiliateUrl, $categoryId, $isFeatured, $isActive, $createdBy) {
        $stmt = $this->db->prepare("
            INSERT INTO coupons (title, slug, description, content, thumbnail_url, affiliate_url, category_id, is_featured, is_active, view_count, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, NOW())
        ");
        $stmt->execute([
            $title,
            $slug,
            $description,
            $content,
            $thumbnailUrl,
            $affiliateUrl,
            $categoryId,
            $isFeatured ? 1 : 0,
            $isActive ? 1 : 0,
            $createdBy
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $title, $slug, $description, $content, $thumbnailUrl, $affiliateUrl, $categoryId, $isFeatured, $isActive) {
        $sql = "UPDATE coupons SET 
                title = ?, 
                slug = ?, 
                description = ?, 
                content = ?, 
                affiliate_url = ?, 
                category_id = ?, 
                is_featured = ?, 
                is_active = ?";
        
        $params = [
            $title,
            $slug,
            $description,
            $content,
            $affiliateUrl,
            $categoryId,
            $isFeatured ? 1 : 0,
            $isActive ? 1 : 0
        ];

        if ($thumbnailUrl !== null) {
            $sql .= ", thumbnail_url = ?";
            $params[] = $thumbnailUrl;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM coupons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function incrementViewCount($id) {
        $stmt = $this->db->prepare("UPDATE coupons SET view_count = view_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
