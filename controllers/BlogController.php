<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Tag.php';

class BlogController extends BaseController {

    public function index() {
        $categoryId = isset($_GET['categoryId']) && $_GET['categoryId'] !== '' ? (int)$_GET['categoryId'] : null;
        $tagId = isset($_GET['tagId']) && $_GET['tagId'] !== '' ? (int)$_GET['tagId'] : null;
        $keyword = isset($_GET['keyword']) && $_GET['keyword'] !== '' ? trim($_GET['keyword']) : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $pageSize = 6; // 6 blog posts per page is standard and fits grid layouts nicely

        $postModel = new Post();
        $categoryModel = new Category();
        $tagModel = new Tag();

        // Fetch published blog posts based on filters
        $totalPosts = $postModel->countActivePosts($categoryId, $tagId, $keyword);
        $posts = $postModel->getActivePosts($categoryId, $tagId, $page, $pageSize, $keyword);

        // Fetch categories (type = blog) and tags list for sidebar
        $blogCategories = $categoryModel->getActiveCategories('blog');
        $allTags = $tagModel->getAll();

        $totalPages = (int)ceil($totalPosts / $pageSize);
        if ($totalPages < 1) $totalPages = 1;

        // Fetch tags for each post in the list
        foreach ($posts as &$post) {
            $post['tags'] = $tagModel->getTagsByPostId($post['id']);
        }

        $this->renderView('blog/index', [
            'Posts' => $posts,
            'BlogCategories' => $blogCategories,
            'AllTags' => $allTags,
            'CurrentPage' => $page,
            'TotalPages' => $totalPages,
            'SelectedCategoryId' => $categoryId,
            'SelectedTagId' => $tagId,
            'Keyword' => $keyword
        ]);
    }

    public function detail() {
        $postModel = new Post();
        $tagModel = new Tag();
        $categoryModel = new Category();

        $post = null;
        if (isset($_GET['slug']) && !empty($_GET['slug'])) {
            $slug = $_GET['slug'];
            $post = $postModel->getBySlug($slug);
        } else {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id > 0) {
                $post = $postModel->getById($id);
                // Ensure only published posts can be viewed on frontend detail page
                if ($post && $post['status'] !== 'published') {
                    $post = null;
                }
            }
        }

        if (!$post) {
            http_response_code(404);
            $this->renderView('shared/404', ['message' => 'Bài viết bạn tìm kiếm không tồn tại hoặc đã được chuyển sang chế độ nháp.'], true);
            exit;
        }

        $id = $post['id'];

        // Increment view count
        $postModel->incrementViewCount($id);

        // Fetch post tags
        $tags = $tagModel->getTagsByPostId($id);

        // Fetch related posts (same category, limit 3)
        $relatedPosts = [];
        if (!empty($post['category_id'])) {
            $relatedPosts = $postModel->getRelatedPosts($id, $post['category_id'], 3);
        }

        // Fetch previous/next posts
        $prevNext = $postModel->getPrevNextPosts($id, $post['published_at'] ?? $post['created_at']);

        // Fetch all categories and tags for frontend sidebar if layout needs it
        $blogCategories = $categoryModel->getActiveCategories('blog');

        $this->renderView('blog/detail', [
            'post' => $post,
            'tags' => $tags,
            'relatedPosts' => $relatedPosts,
            'prevPost' => $prevNext['prev'],
            'nextPost' => $prevNext['next'],
            'blogCategories' => $blogCategories
        ]);
    }
}
