<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getBySlug($slug) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.fullname as author_name, u.avatar as author_avatar, c.name as category_name, c.slug as category_slug
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.status = 'published'
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function slugExists($slug, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM posts WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM posts WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function getAllForAdmin($keyword = null, $status = null, $categoryId = null) {
        $sql = "
            SELECT p.*, u.fullname as author_name, c.name as category_name
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE ? OR p.summary LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        if (!empty($status)) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        if (!empty($categoryId)) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getActivePosts($categoryId = null, $tagId = null, $page = 1, $pageSize = 8, $keyword = null) {
        $offset = ($page - 1) * $pageSize;
        $sql = "
            SELECT DISTINCT p.*, u.fullname as author_name, c.name as category_name, c.slug as category_slug
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
        ";

        if ($tagId !== null) {
            $sql .= " JOIN post_tags pt ON p.id = pt.post_id ";
        }

        $sql .= " WHERE p.status = 'published' ";
        $params = [];

        if ($categoryId !== null) {
            $sql .= " AND p.category_id = ? ";
            $params[] = $categoryId;
        }

        if ($tagId !== null) {
            $sql .= " AND pt.tag_id = ? ";
            $params[] = $tagId;
        }

        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE ? OR p.summary LIKE ? OR p.content LIKE ?) ";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $sql .= " ORDER BY p.published_at DESC LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        
        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex, $param);
            $paramIndex++;
        }
        $stmt->bindValue($paramIndex, (int)$pageSize, PDO::PARAM_INT);
        $paramIndex++;
        $stmt->bindValue($paramIndex, (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countActivePosts($categoryId = null, $tagId = null, $keyword = null) {
        $sql = "SELECT COUNT(DISTINCT p.id) FROM posts p";
        if ($tagId !== null) {
            $sql .= " JOIN post_tags pt ON p.id = pt.post_id";
        }
        $sql .= " WHERE p.status = 'published'";
        $params = [];

        if ($categoryId !== null) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($tagId !== null) {
            $sql .= " AND pt.tag_id = ?";
            $params[] = $tagId;
        }

        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE ? OR p.summary LIKE ? OR p.content LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function add($title, $slug, $summary, $content, $thumbnail, $status, $authorId, $categoryId, $seoTitle, $seoDescription, $canonicalUrl, $publishedAt = null) {
        $readingTime = $this->calculateReadingTime($content);
        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        $stmt = $this->db->prepare("
            INSERT INTO posts (title, slug, summary, content, thumbnail, status, published_at, author_id, category_id, reading_time, seo_title, seo_description, canonical_url, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $success = $stmt->execute([
            $title, $slug, $summary, $content, $thumbnail, $status, $publishedAt, $authorId, $categoryId, $readingTime, $seoTitle, $seoDescription, $canonicalUrl
        ]);

        return $success ? $this->db->lastInsertId() : false;
    }

    public function update($id, $title, $slug, $summary, $content, $thumbnail, $status, $categoryId, $seoTitle, $seoDescription, $canonicalUrl, $publishedAt = null) {
        $readingTime = $this->calculateReadingTime($content);
        
        // Manage published_at automatically
        $existing = $this->getById($id);
        if ($status === 'published' && ($existing['status'] !== 'published' || empty($existing['published_at']))) {
            $publishedAt = date('Y-m-d H:i:s');
        } else {
            $publishedAt = $publishedAt ?? $existing['published_at'];
        }

        // Keep existing thumbnail if none uploaded
        if (empty($thumbnail)) {
            $thumbnail = $existing['thumbnail'];
        }

        $stmt = $this->db->prepare("
            UPDATE posts 
            SET title = ?, slug = ?, summary = ?, content = ?, thumbnail = ?, status = ?, published_at = ?, category_id = ?, reading_time = ?, seo_title = ?, seo_description = ?, canonical_url = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $title, $slug, $summary, $content, $thumbnail, $status, $publishedAt, $categoryId, $readingTime, $seoTitle, $seoDescription, $canonicalUrl, $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function incrementViewCount($id) {
        $stmt = $this->db->prepare("UPDATE posts SET view_count = view_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRelatedPosts($id, $categoryId, $limit = 3) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id != ? AND p.category_id = ? AND p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$id, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$categoryId, PDO::PARAM_INT);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPrevNextPosts($id, $publishedAt) {
        $prevStmt = $this->db->prepare("
            SELECT title, slug FROM posts 
            WHERE status = 'published' AND published_at < ? 
            ORDER BY published_at DESC LIMIT 1
        ");
        $prevStmt->execute([$publishedAt]);
        $prev = $prevStmt->fetch();

        $nextStmt = $this->db->prepare("
            SELECT title, slug FROM posts 
            WHERE status = 'published' AND published_at > ? 
            ORDER BY published_at ASC LIMIT 1
        ");
        $nextStmt->execute([$publishedAt]);
        $next = $nextStmt->fetch();

        return ['prev' => $prev, 'next' => $next];
    }

    public function getPostsForFeed($limit = 20) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.fullname as author_name, c.name as category_name
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function calculateReadingTime($content) {
        $wordCount = str_word_count(strip_tags($content));
        // Average reading speed: 200 words per minute
        $minutes = ceil($wordCount / 200);
        if ($minutes < 1) $minutes = 1;
        return $minutes . " min read";
    }
}
