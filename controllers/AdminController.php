<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Coupon.php';
require_once __DIR__ . '/../models/Voucher.php';
require_once __DIR__ . '/../models/CouponFAQ.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Media.php';

class AdminController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->authorizeAdmin();
    }

    protected function renderView($viewPath, $data = [], $includeLayout = true) {
        extract($data);
        
        // Pass variables to view scope
        $username = isset($_SESSION['Username']) ? $_SESSION['Username'] : null;
        $roleId = isset($_SESSION['RoleId']) ? $_SESSION['RoleId'] : null;
        $userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;

        // Fetch temp messages
        $success = $data['success'] ?? $this->getTempData('success');
        $error = $data['error'] ?? $this->getTempData('error');

        if ($includeLayout) {
            require_once __DIR__ . '/../views/shared/admin_header.php';
        }

        $fullViewPath = __DIR__ . "/../views/" . $viewPath . ".php";
        if (file_exists($fullViewPath)) {
            require $fullViewPath;
        } else {
            echo "View not found: " . $viewPath;
        }

        if ($includeLayout) {
            require_once __DIR__ . '/../views/shared/admin_footer.php';
        }
        
        // Clear TempData after view is rendered
        if (method_exists($this, 'clearTempData')) {
            $this->clearTempData();
        }
    }

    public function dashboardadmin() {
        $keyword = $_GET['keyword'] ?? null;
        $categoryId = isset($_GET['categoryId']) && $_GET['categoryId'] !== '' ? (int)$_GET['categoryId'] : null;
        $isActive = isset($_GET['isActive']) && $_GET['isActive'] !== '' ? (int)$_GET['isActive'] : null;

        $couponModel = new Coupon();
        $categoryModel = new Category();

        $stores = $couponModel->getAllForAdmin($keyword, $categoryId, $isActive);
        $categories = $categoryModel->getAllCategories();

        $this->renderView('admin/dashboard', [
            'stores' => $stores,
            'categories' => $categories,
            'keyword' => $keyword,
            'selectedCategoryId' => $categoryId,
            'isActive' => $isActive,
            'success' => $this->getTempData('success')
        ]);
    }

    public function detailstore() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) $this->redirect('/admin/dashboardadmin');

        $couponModel = new Coupon();
        $voucherModel = new Voucher();
        $faqModel = new CouponFAQ();

        $coupon = $couponModel->getByIdWithDetails($id);
        if (!$coupon) $this->redirect('/admin/dashboardadmin');

        $vouchers = $voucherModel->getByCouponId($id);
        $faqs = $faqModel->getByCouponId($id);
        // Fallback to default FAQs if none are provided for this coupon
        if (empty($faqs)) {
            // Load default FAQs and replace placeholder with coupon title
            require_once __DIR__ . '/../models/DefaultFAQ.php';
            $faqs = DefaultFAQ::generate($coupon['title'] ?? '');
        }
        $bestOffer = Voucher::getBestOffer($vouchers);

        $this->renderView('admin/detail_store', [
            'coupon' => $coupon,
            'vouchers' => $vouchers,
            'faqs' => $faqs,
            'bestOffer' => $bestOffer
        ]);
    }

    public function addstore() {
        $categoryModel = new Category();
        $categories = $categoryModel->getActiveCategories();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAntiForgeryToken();

            $title = trim($_POST['Title'] ?? '');
            $slug = trim($_POST['Slug'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $content = trim($_POST['AboutStore'] ?? '');
            $affiliateUrl = trim($_POST['AffiliateUrl'] ?? '');
            $categoryId = (int)($_POST['CategoryId'] ?? 0);
            $isFeatured = isset($_POST['IsFeatured']) ? 1 : 0;
            $isActive = isset($_POST['IsActive']) ? 1 : 0;
            $faqText = trim($_POST['FaqText'] ?? '');

            if (empty($title) || empty($slug) || empty($affiliateUrl) || $categoryId <= 0) {
                $error = "Vui lòng nhập đầy đủ các trường bắt buộc";
            } else {
                // Handle image upload
                $imagePath = null;
                if (isset($_FILES['ImageFile']) && $_FILES['ImageFile']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['ImageFile']['tmp_name'];
                    $fileName = $_FILES['ImageFile']['name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = __DIR__ . '/../public/uploads/stores/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $destPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        chmod($destPath, 0644); // Set read permission for public access on Linux hosting
                        $imagePath = '/uploads/stores/' . $newFileName;
                    }
                }

                $couponModel = new Coupon();
                $createdBy = $_SESSION['UserId'] ?? 1;

                $newCouponId = $couponModel->add(
                    $title, 
                    $slug, 
                    $description, 
                    $content, 
                    $imagePath, 
                    $affiliateUrl, 
                    $categoryId, 
                    $isFeatured, 
                    $isActive, 
                    $createdBy
                );

                if ($newCouponId) {
                    // Process FAQs
                    if (!empty($faqText)) {
                        $faqModel = new CouponFAQ();
                        $lines = explode("\n", $faqText);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line)) continue;
                            
                            $parts = explode('|', $line, 2);
                            if (count($parts) >= 2) {
                                $faqModel->add($newCouponId, trim($parts[0]), trim($parts[1]));
                            }
                        }
                    }

                    $this->setTempData('success', 'Thêm store thành công');
                    $this->redirect('/admin/dashboardadmin');
                } else {
                    $error = "Có lỗi xảy ra khi tạo store";
                }
            }
        }

        $this->renderView('admin/add_store', [
            'categories' => $categories,
            'error' => $error
        ]);
    }

    public function editstore() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) $this->redirect('/admin/dashboardadmin');

        $couponModel = new Coupon();
        $categoryModel = new Category();
        $faqModel = new CouponFAQ();

        $coupon = $couponModel->getById($id);
        if (!$coupon) $this->redirect('/admin/dashboardadmin');

        $categories = $categoryModel->getActiveCategories();
        $faqs = $faqModel->getByCouponId($id);
        
        // Format FAQs to string Question|Answer
        $faqLines = [];
        foreach ($faqs as $faq) {
            $faqLines[] = $faq['question'] . '|' . $faq['answer'];
        }
        $faqText = implode("\n", $faqLines);

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAntiForgeryToken();

            $title = trim($_POST['Title'] ?? '');
            $slug = trim($_POST['Slug'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $content = trim($_POST['AboutStore'] ?? '');
            $affiliateUrl = trim($_POST['AffiliateUrl'] ?? '');
            $categoryId = (int)($_POST['CategoryId'] ?? 0);
            $isFeatured = isset($_POST['IsFeatured']) ? 1 : 0;
            $isActive = isset($_POST['IsActive']) ? 1 : 0;
            $faqTextNew = trim($_POST['FaqText'] ?? '');

            if (empty($title) || empty($slug) || empty($affiliateUrl) || $categoryId <= 0) {
                $error = "Vui lòng nhập đầy đủ các trường bắt buộc";
            } else {
                // Handle optional image upload
                $imagePath = null;
                if (isset($_FILES['ThumbnailFile']) && $_FILES['ThumbnailFile']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['ThumbnailFile']['tmp_name'];
                    $fileName = $_FILES['ThumbnailFile']['name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = __DIR__ . '/../public/uploads/stores/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $destPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        chmod($destPath, 0644); // Set read permission for public access on Linux hosting
                        $imagePath = '/uploads/stores/' . $newFileName;
                    } 
                }

                $success = $couponModel->update(
                    $id,
                    $title,
                    $slug,
                    $description,
                    $content,
                    $imagePath,
                    $affiliateUrl,
                    $categoryId,
                    $isFeatured,
                    $isActive
                );

                if ($success) {
                    // Update FAQs: Remove old, insert new
                    $faqModel->deleteByCouponId($id);

                    if (!empty($faqTextNew)) {
                        $lines = explode("\n", $faqTextNew);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line)) continue;
                            
                            $parts = explode('|', $line, 2);
                            if (count($parts) >= 2) {
                                $faqModel->add($id, trim($parts[0]), trim($parts[1]));
                            }
                        }
                    }

                    $this->setTempData('success', 'Cập nhật store thành công');
                    $this->redirect('/admin/dashboardadmin');
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật store";
                }
            }
        }

        $this->renderView('admin/edit_store', [
            'coupon' => $coupon,
            'categories' => $categories,
            'faqText' => $faqText,
            'error' => $error
        ]);
    }

    public function deletestore() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) $this->redirect('/admin/dashboardadmin');

        $couponModel = new Coupon();
        $voucherModel = new Voucher();
        $faqModel = new CouponFAQ();

        // Foreign keys cascade delete in SQL will handle coupon clicks,
        // but we can explicitly delete vouchers and faqs first for safety
        $faqModel->deleteByCouponId($id);
        $voucherModel->deleteByCouponId($id);
        $couponModel->delete($id);

        $this->setTempData('success', 'Xóa store thành công');
        $this->redirect('/admin/dashboardadmin');
    }

    // ========================
    // CATEGORY MANAGEMENT
    // ========================

    public function categorymanagement() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        $this->renderView('admin/categories', [
            'categories' => $categories,
            'success' => $this->getTempData('success'),
            'error' => $this->getTempData('error')
        ]);
    }

    public function addcategory() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAntiForgeryToken();

            $name = trim($_POST['Name'] ?? '');
            $slug = trim($_POST['Slug'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $isActive = isset($_POST['IsActive']) ? 1 : 0;

            if (empty($name) || empty($slug)) {
                $error = "Vui lòng nhập đầy đủ Name và Slug";
            } else {
                $categoryModel = new Category();
                if ($categoryModel->slugExists($slug)) {
                    $error = "Slug đã tồn tại";
                } else {
                    $success = $categoryModel->add($name, $slug, $description, $isActive);
                    if ($success) {
                        $this->setTempData('success', 'Thêm danh mục thành công');
                        $this->redirect('/admin/categorymanagement');
                    } else {
                        $error = "Có lỗi xảy ra khi thêm danh mục";
                    }
                }
            }
        }

        $this->renderView('admin/add_category', [
            'error' => $error
        ]);
    }

    public function editcategory() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) $this->redirect('/admin/categorymanagement');

        $categoryModel = new Category();
        $category = $categoryModel->getById($id);
        if (!$category) $this->redirect('/admin/categorymanagement');

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['Name'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $isActive = isset($_POST['IsActive']) ? 1 : 0;

            if (empty($name)) {
                $error = "Tên danh mục không được để trống";
            } else {
                // Auto generate slug
                $slug = $this->generateSlug($name);

                $success = $categoryModel->update($id, $name, $slug, $description, $isActive);
                if ($success) {
                    $this->setTempData('success', 'Cập nhật danh mục thành công');
                    $this->redirect('/admin/categorymanagement');
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật danh mục";
                }
            }
        }

        $this->renderView('admin/edit_category', [
            'category' => $category,
            'error' => $error
        ]);
    }

    public function deletecategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $categoryModel = new Category();
                try {
                    $success = $categoryModel->delete($id);
                    if ($success) {
                        $this->setTempData('success', 'Xóa danh mục thành công');
                    } else {
                        $this->setTempData('error', 'Không thể xóa danh mục này');
                    }
                } catch (PDOException $e) {
                    $this->setTempData('error', 'Lỗi: Danh mục này đang chứa store, không thể xóa!');
                }
            }
        }
        $this->redirect('/admin/categorymanagement');
    }

    // ========================
    // OFFER (VOUCHER) MANAGEMENT
    // ========================

    public function offermanagement() {
        $keyword = $_GET['keyword'] ?? null;
        $status = $_GET['status'] ?? null;
        $type = $_GET['type'] ?? null;

        $voucherModel = new Voucher();
        $offers = $voucherModel->getAllForAdmin($keyword, $status, $type);

        $couponModel = new Coupon();
        $coupons = $couponModel->getAllForAdmin(); // For dropdowns in Edit Offer modall

        $this->renderView('admin/offers', [
            'offers' => $offers,
            'coupons' => $coupons,
            'keyword' => $keyword,
            'status' => $status,
            'type' => $type,
            'success' => $this->getTempData('success'),
            'error' => $this->getTempData('error')
        ]);
    }

    public function addoffer() {
        $couponModel = new Coupon();
        $coupons = $couponModel->getAllForAdmin(); // Fetch active stores
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $couponId = (int)($_POST['CouponId'] ?? 0);
            $voucherCode = trim($_POST['VoucherCode'] ?? '');
            $discountText = trim($_POST['DiscountText'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $startDate = $_POST['StartDate'] ?? null;
            $expiredDate = $_POST['ExpiredDate'] ?? null;
            $isFeatured = isset($_POST['IsFeatured']) ? 1 : 0;
            $isActive = isset($_POST['IsActive']) ? 1 : 0;

            if ($couponId <= 0) {
                $error = "Vui lòng chọn Coupon/Store";
            } else if (empty($voucherCode)) {
                $error = "Vui lòng nhập Voucher Code";
            } else {
                if (empty($description)) {
                    $description = Voucher::generateDescription($voucherCode, $discountText);
                }
                $voucherModel = new Voucher();
                $success = $voucherModel->add($couponId, $voucherCode, $discountText, $description, $startDate, $expiredDate, $isFeatured, $isActive);
                if ($success) {
                    $this->setTempData('success', 'Thêm Offer thành công');
                    $this->redirect('/admin/offermanagement');
                } else {
                    $error = "Có lỗi xảy ra khi thêm Offer";
                }
            }
        }

        $this->renderView('admin/add_offer', [
            'coupons' => $coupons,
            'error' => $error
        ]);
    }

    public function editoffer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['Id'] ?? 0);
            $couponId = (int)($_POST['CouponId'] ?? 0);
            $voucherCode = trim($_POST['VoucherCode'] ?? '');
            $discountText = trim($_POST['DiscountText'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $startDate = $_POST['StartDate'] ?? null;
            $expiredDate = $_POST['ExpiredDate'] ?? null;
            $isFeatured = isset($_POST['IsFeatured']) ? 1 : 0;
            $isActive = isset($_POST['IsActive']) ? 1 : 0;

            if ($id > 0 && $couponId > 0 && !empty($voucherCode)) {
                if (empty($description)) {
                    $description = Voucher::generateDescription($voucherCode, $discountText);
                }
                $voucherModel = new Voucher();
                $success = $voucherModel->update($id, $couponId, $voucherCode, $discountText, $description, $startDate, $expiredDate, $isFeatured, $isActive);
                if ($success) {
                    $this->setTempData('success', 'Cập nhật Offer thành công');
                } else {
                    $this->setTempData('error', 'Có lỗi xảy ra khi cập nhật Offer');
                }
            }
        }
        $this->redirect('/admin/offermanagement');
    }

    public function deleteoffer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $voucherModel = new Voucher();
                $success = $voucherModel->delete($id);
                if ($success) {
                    $this->setTempData('success', 'Xóa Offer thành công');
                } else {
                    $this->setTempData('error', 'Có lỗi xảy ra khi xóa Offer');
                }
            }
        }
        $this->redirect('/admin/offermanagement');
    }

    // ========================
    // USER MANAGEMENT
    // ========================

    public function usermanagement() {
        $userModel = new User();
        $users = $userModel->getAllForAdmin();

        $this->renderView('admin/users', [
            'users' => $users,
            'success' => $this->getTempData('success'),
            'error' => $this->getTempData('error')
        ]);
    }

    public function adduser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['Username'] ?? '');
            $email = trim($_POST['Email'] ?? '');
            $password = $_POST['Password'] ?? '';
            $fullName = trim($_POST['FullName'] ?? '');
            $roleId = (int)($_POST['RoleId'] ?? 2);
            $isActive = isset($_POST['IsActive']) ? 1 : 0;

            if (!empty($username) && !empty($email) && !empty($password)) {
                $userModel = new User();
                if ($userModel->usernameOrEmailExists($username, $email)) {
                    $this->setTempData('error', 'Username hoặc Email đã tồn tại');
                } else {
                    $passwordHash = base64_encode(hash('sha256', $password, true));
                    $success = $userModel->add($username, $email, $passwordHash, $fullName, $roleId, $isActive);
                    if ($success) {
                        $this->setTempData('success', 'Thêm user thành công');
                    } else {
                        $this->setTempData('error', 'Có lỗi xảy ra khi thêm user');
                    }
                }
            }
        }
        $this->redirect('/admin/usermanagement');
    }

    public function edituser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['Id'] ?? 0);
            $username = trim($_POST['Username'] ?? '');
            $email = trim($_POST['Email'] ?? '');
            $fullName = trim($_POST['FullName'] ?? '');
            $roleId = (int)($_POST['RoleId'] ?? 2);
            $isActive = isset($_POST['IsActive']) ? 1 : 0;

            if ($id > 0 && !empty($username) && !empty($email)) {
                $userModel = new User();
                if ($userModel->usernameOrEmailExists($username, $email, $id)) {
                    $this->setTempData('error', 'Username hoặc Email đã tồn tại');
                } else {
                    $success = $userModel->update($id, $username, $email, $fullName, $roleId, $isActive);
                    if ($success) {
                        $this->setTempData('success', 'Cập nhật user thành công');
                    } else {
                        $this->setTempData('error', 'Có lỗi xảy ra khi cập nhật user');
                    }
                }
            }
        }
        $this->redirect('/admin/usermanagement');
    }

    public function deleteuser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                // Don't let users delete their own account
                if ($id == $_SESSION['UserId']) {
                    $this->setTempData('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
                } else {
                    $userModel = new User();
                    $success = $userModel->delete($id);
                    if ($success) {
                        $this->setTempData('success', 'Xóa user thành công');
                    } else {
                        $this->setTempData('error', 'Có lỗi xảy ra khi xóa user');
                    }
                }
            }
        }
        $this->redirect('/admin/usermanagement');
    }

    // ========================
    // BLOG MANAGEMENT ACTIONS
    // ========================

    public function blogposts() {
        $keyword = $_GET['keyword'] ?? null;
        $categoryId = isset($_GET['categoryId']) && $_GET['categoryId'] !== '' ? (int)$_GET['categoryId'] : null;
        $status = $_GET['status'] ?? null;

        $postModel = new Post();
        $categoryModel = new Category();

        $posts = $postModel->getAllForAdmin($keyword, $status, $categoryId);
        $blogCategories = $categoryModel->getAllCategories('blog');

        $this->renderView('admin/blog/posts', [
            'posts' => $posts,
            'blogCategories' => $blogCategories,
            'keyword' => $keyword,
            'selectedCategoryId' => $categoryId,
            'status' => $status,
            'success' => $this->getTempData('success'),
            'error' => $this->getTempData('error')
        ]);
    }

    public function addblogpost() {
        $categoryModel = new Category();
        $userModel = new User();
        $blogCategories = $categoryModel->getActiveCategories('blog');
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAntiForgeryToken();

            $title = trim($_POST['Title'] ?? '');
            $slug = trim($_POST['Slug'] ?? '');
            $summary = trim($_POST['Summary'] ?? '');
            $content = trim($_POST['Content'] ?? '');
            $status = $_POST['Status'] ?? 'draft';
            $categoryId = isset($_POST['CategoryId']) && $_POST['CategoryId'] !== '' ? (int)$_POST['CategoryId'] : null;
            $seoTitle = trim($_POST['SeoTitle'] ?? '');
            $seoDescription = trim($_POST['SeoDescription'] ?? '');
            $canonicalUrl = trim($_POST['CanonicalUrl'] ?? '');
            $tagsInput = trim($_POST['Tags'] ?? '');

            if (empty($title) || empty($slug)) {
                $error = "Vui lòng nhập đầy đủ tiêu đề và slug bài viết";
            } else {
                $postModel = new Post();
                if ($postModel->slugExists($slug)) {
                    $error = "Slug bài viết đã tồn tại";
                } else {
                    // Handle image upload
                    $thumbnailPath = null;
                    if (isset($_FILES['ThumbnailFile']) && $_FILES['ThumbnailFile']['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['ThumbnailFile']['tmp_name'];
                        $fileName = $_FILES['ThumbnailFile']['name'];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        
                        $newFileName = uniqid() . '.' . $fileExtension;
                        $uploadDir = ROOT_PATH . '/public/uploads/blog/';
                        
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        $destPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            chmod($destPath, 0644);
                            $thumbnailPath = '/uploads/blog/' . $newFileName;
                            
                            // Log thumbnail to Media library
                            $mediaModel = new Media();
                            $mediaModel->add($thumbnailPath, $fileName, 'Post Thumbnail: ' . $title, 'image');
                        }
                    }

                    $authorId = $_SESSION['UserId'] ?? 1;

                    $newPostId = $postModel->add(
                        $title, $slug, $summary, $content, $thumbnailPath, $status, 
                        $authorId, $categoryId, $seoTitle, $seoDescription, $canonicalUrl
                    );

                    if ($newPostId) {
                        // Process tags
                        if (!empty($tagsInput)) {
                            $tagNames = explode(',', $tagsInput);
                            $tagModel = new Tag();
                            $tagModel->updatePostTags($newPostId, $tagNames);
                        }

                        // Regenerate SEO files
                        $this->regenerateSitemapAndRss();

                        $this->setTempData('success', 'Thêm bài viết blog thành công');
                        $this->redirect('/admin/blogposts');
                    } else {
                        $error = "Có lỗi xảy ra khi tạo bài viết";
                    }
                }
            }
        }

        $this->renderView('admin/blog/add_post', [
            'blogCategories' => $blogCategories,
            'error' => $error
        ]);
    }

    public function editblogpost() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) $this->redirect('/admin/blogposts');

        $postModel = new Post();
        $categoryModel = new Category();
        $tagModel = new Tag();

        $post = $postModel->getById($id);
        if (!$post) $this->redirect('/admin/blogposts');

        $blogCategories = $categoryModel->getActiveCategories('blog');
        
        // Fetch current tags
        $postTags = $tagModel->getTagsByPostId($id);
        $tagNames = array_column($postTags, 'name');
        $tagText = implode(', ', $tagNames);

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAntiForgeryToken();

            $title = trim($_POST['Title'] ?? '');
            $slug = trim($_POST['Slug'] ?? '');
            $summary = trim($_POST['Summary'] ?? '');
            $content = trim($_POST['Content'] ?? '');
            $status = $_POST['Status'] ?? 'draft';
            $categoryId = isset($_POST['CategoryId']) && $_POST['CategoryId'] !== '' ? (int)$_POST['CategoryId'] : null;
            $seoTitle = trim($_POST['SeoTitle'] ?? '');
            $seoDescription = trim($_POST['SeoDescription'] ?? '');
            $canonicalUrl = trim($_POST['CanonicalUrl'] ?? '');
            $tagsInput = trim($_POST['Tags'] ?? '');

            if (empty($title) || empty($slug)) {
                $error = "Vui lòng nhập đầy đủ tiêu đề và slug bài viết";
            } else if ($postModel->slugExists($slug, $id)) {
                $error = "Slug bài viết đã tồn tại";
            } else {
                // Handle optional image upload
                $thumbnailPath = null;
                if (isset($_FILES['ThumbnailFile']) && $_FILES['ThumbnailFile']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['ThumbnailFile']['tmp_name'];
                    $fileName = $_FILES['ThumbnailFile']['name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = ROOT_PATH . '/public/uploads/blog/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $destPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        chmod($destPath, 0644);
                        $thumbnailPath = '/uploads/blog/' . $newFileName;

                        // Log to Media library
                        $mediaModel = new Media();
                        $mediaModel->add($thumbnailPath, $fileName, 'Post Thumbnail: ' . $title, 'image');
                    }
                }

                $success = $postModel->update(
                    $id, $title, $slug, $summary, $content, $thumbnailPath, $status, 
                    $categoryId, $seoTitle, $seoDescription, $canonicalUrl
                );

                if ($success) {
                    // Update tags
                    $tagModel->updatePostTags($id, explode(',', $tagsInput));

                    // Regenerate SEO files
                    $this->regenerateSitemapAndRss();

                    $this->setTempData('success', 'Cập nhật bài viết blog thành công');
                    $this->redirect('/admin/blogposts');
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật bài viết";
                }
            }
        }

        $this->renderView('admin/blog/edit_post', [
            'post' => $post,
            'tagText' => $tagText,
            'blogCategories' => $blogCategories,
            'error' => $error
        ]);
    }

    public function deleteblogpost() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $postModel = new Post();
            $postModel->delete($id);
            
            // Regenerate SEO files
            $this->regenerateSitemapAndRss();

            $this->setTempData('success', 'Xóa bài viết blog thành công');
        }
        $this->redirect('/admin/blogposts');
    }

    // ========================
    // BLOG CATEGORIES ACTIONS
    // ========================

    public function blogcategories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories('blog');

        $this->renderView('admin/blog/categories', [
            'categories' => $categories,
            'success' => $this->getTempData('success'),
            'error' => $this->getTempData('error')
        ]);
    }

    public function addblogcategory() {
        $categoryModel = new Category();
        $parentCategories = $categoryModel->getActiveCategories('blog');
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAntiForgeryToken();

            $name = trim($_POST['Name'] ?? '');
            $slug = trim($_POST['Slug'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $isActive = isset($_POST['IsActive']) ? 1 : 0;
            $parentId = isset($_POST['ParentId']) && $_POST['ParentId'] !== '' ? (int)$_POST['ParentId'] : null;

            if (empty($name) || empty($slug)) {
                $error = "Vui lòng nhập đầy đủ tên và slug danh mục";
            } else if ($categoryModel->slugExists($slug)) {
                $error = "Slug danh mục đã tồn tại";
            } else {
                $success = $categoryModel->add($name, $slug, $description, $isActive, 'blog', $parentId);
                if ($success) {
                    $this->setTempData('success', 'Thêm danh mục blog thành công');
                    $this->redirect('/admin/blogcategories');
                } else {
                    $error = "Có lỗi xảy ra khi thêm danh mục";
                }
            }
        }

        $this->renderView('admin/blog/add_category', [
            'parentCategories' => $parentCategories,
            'error' => $error
        ]);
    }

    public function editblogcategory() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) $this->redirect('/admin/blogcategories');

        $categoryModel = new Category();
        $category = $categoryModel->getById($id);
        if (!$category) $this->redirect('/admin/blogcategories');

        $parentCategories = $categoryModel->getActiveCategories('blog');
        // Exclude current category from parent selection to prevent cyclic hierarchy
        $parentCategories = array_filter($parentCategories, function($cat) use ($id) {
            return $cat['id'] != $id;
        });

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['Name'] ?? '');
            $description = trim($_POST['Description'] ?? '');
            $isActive = isset($_POST['IsActive']) ? 1 : 0;
            $parentId = isset($_POST['ParentId']) && $_POST['ParentId'] !== '' ? (int)$_POST['ParentId'] : null;

            if (empty($name)) {
                $error = "Tên danh mục không được để trống";
            } else {
                $slug = $this->generateSlug($name);
                
                $success = $categoryModel->update($id, $name, $slug, $description, $isActive, $parentId);
                if ($success) {
                    $this->setTempData('success', 'Cập nhật danh mục blog thành công');
                    $this->redirect('/admin/blogcategories');
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật danh mục";
                }
            }
        }

        $this->renderView('admin/blog/edit_category', [
            'category' => $category,
            'parentCategories' => $parentCategories,
            'error' => $error
        ]);
    }

    public function deleteblogcategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $categoryModel = new Category();
                try {
                    $success = $categoryModel->delete($id);
                    if ($success) {
                        $this->setTempData('success', 'Xóa danh mục blog thành công');
                    } else {
                        $this->setTempData('error', 'Không thể xóa danh mục này');
                    }
                } catch (PDOException $e) {
                    $this->setTempData('error', 'Lỗi: Danh mục này đang chứa bài viết hoặc có danh mục con, không thể xóa!');
                }
            }
        }
        $this->redirect('/admin/blogcategories');
    }

    // ========================
    // BLOG TAGS ACTIONS
    // ========================

    public function blogtags() {
        $tagModel = new Tag();
        $tags = $tagModel->getAll();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['Name'] ?? '');
            if (!empty($name)) {
                $slug = $this->generateSlug($name);
                if ($tagModel->slugExists($slug)) {
                    $error = "Tag này đã tồn tại";
                } else {
                    $tagModel->add($name, $slug);
                    $this->setTempData('success', 'Thêm tag thành công');
                    $this->redirect('/admin/blogtags');
                }
            } else {
                $error = "Tên tag không được để trống";
            }
        }

        $this->renderView('admin/blog/tags', [
            'tags' => $tags,
            'error' => $error,
            'success' => $this->getTempData('success')
        ]);
    }

    public function deleteblogtag() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $tagModel = new Tag();
                $tagModel->delete($id);
                $this->setTempData('success', 'Xóa tag thành công');
            }
        }
        $this->redirect('/admin/blogtags');
    }

    // ========================
    // MEDIA LIBRARY ACTIONS
    // ========================

    public function medialibrary() {
        $mediaModel = new Media();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['MediaFile']) && $_FILES['MediaFile']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['MediaFile']['tmp_name'];
                $fileName = $_FILES['MediaFile']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadDir = ROOT_PATH . '/public/uploads/media/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $destPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    chmod($destPath, 0644);
                    $url = '/uploads/media/' . $newFileName;
                    
                    $alt = trim($_POST['Alt'] ?? '');
                    $caption = trim($_POST['Caption'] ?? '');

                    $mediaModel->add($url, $alt, $caption, 'image');
                    $this->setTempData('success', 'Upload hình ảnh thành công');
                    $this->redirect('/admin/medialibrary');
                } else {
                    $error = "Có lỗi xảy ra khi di chuyển file upload";
                }
            } else {
                $error = "Vui lòng chọn file hợp lệ để upload";
            }
        }

        $mediaFiles = $mediaModel->getAll();

        $this->renderView('admin/blog/media', [
            'mediaFiles' => $mediaFiles,
            'error' => $error,
            'success' => $this->getTempData('success')
        ]);
    }

    public function deletemedia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id > 0) {
                $mediaModel = new Media();
                $media = $mediaModel->getById($id);
                if ($media) {
                    // Try to delete physical file
                    $filePath = ROOT_PATH . '/public' . $media['url'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $mediaModel->delete($id);
                    $this->setTempData('success', 'Xóa file thành công');
                }
            }
        }
        $this->redirect('/admin/medialibrary');
    }

    public function uploadmediaajax() {
        // Ajax image upload handler for TinyMCE rich editor
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadDir = ROOT_PATH . '/public/uploads/media/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $destPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    chmod($destPath, 0644);
                    $url = '/uploads/media/' . $newFileName;
                    
                    // Log to media table
                    $mediaModel = new Media();
                    $mediaModel->add($url, $fileName, 'Uploaded via Rich Editor', 'image');
                    
                    echo json_encode(['location' => $url]);
                    exit;
                }
            }
        }
        http_response_code(500);
        echo json_encode(['error' => 'Upload failed']);
        exit;
    }

    // ========================
    // SEO AUTOMATION HELPERS
    // ========================

    private function regenerateSitemapAndRss() {
        try {
            $host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

            // 1. GENERATE RSS FEED
            $postModel = new Post();
            $feedPosts = $postModel->getPostsForFeed(20);

            $rss = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
            $rss .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
            $rss .= "<channel>\n";
            $rss .= "    <title>Peak Vouch - Blog chia sẻ kiến thức &amp; mã giảm giá</title>\n";
            $rss .= "    <link>" . htmlspecialchars($host) . "/blog</link>\n";
            $rss .= "    <description>Chia sẻ mã giảm giá hot, kinh nghiệm mua sắm tiết kiệm và kiến thức tiêu dùng thông thái.</description>\n";
            $rss .= "    <language>vi-vn</language>\n";
            $rss .= "    <atom:link href=\"" . htmlspecialchars($host) . "/rss.xml\" rel=\"self\" type=\"application/rss+xml\" />\n";

            foreach ($feedPosts as $post) {
                $pubDate = date(DATE_RSS, strtotime($post['published_at'] ?? $post['created_at']));
                $rss .= "    <item>\n";
                $rss .= "        <title>" . htmlspecialchars($post['title']) . "</title>\n";
                $rss .= "        <link>" . htmlspecialchars($host . '/blog/' . $post['slug']) . "</link>\n";
                $rss .= "        <guid isPermaLink=\"true\">" . htmlspecialchars($host . '/blog/' . $post['slug']) . "</guid>\n";
                $rss .= "        <description>" . htmlspecialchars($post['summary'] ?? '') . "</description>\n";
                $rss .= "        <pubDate>" . $pubDate . "</pubDate>\n";
                if (!empty($post['author_name'])) {
                    $rss .= "        <author>" . htmlspecialchars($post['author_name']) . "</author>\n";
                }
                if (!empty($post['category_name'])) {
                    $rss .= "        <category>" . htmlspecialchars($post['category_name']) . "</category>\n";
                }
                $rss .= "    </item>\n";
            }
            $rss .= "</channel>\n";
            $rss .= "</rss>\n";

            file_put_contents(ROOT_PATH . '/public/rss.xml', $rss);

            // 2. GENERATE SITEMAP
            $couponModel = new Coupon();
            $stores = $couponModel->getActiveCoupons(null, 1, 1000); // Fetch active stores

            $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
            
            // Core pages
            $corePages = [
                '/' => '1.0',
                '/home/about' => '0.5',
                '/home/contact' => '0.5',
                '/blog' => '0.8'
            ];

            foreach ($corePages as $path => $priority) {
                $sitemap .= "    <url>\n";
                $sitemap .= "        <loc>" . htmlspecialchars($host . $path) . "</loc>\n";
                $sitemap .= "        <changefreq>daily</changefreq>\n";
                $sitemap .= "        <priority>" . $priority . "</priority>\n";
                $sitemap .= "    </url>\n";
            }

            // Coupon Store pages
            foreach ($stores as $store) {
                $sitemap .= "    <url>\n";
                $sitemap .= "        <loc>" . htmlspecialchars($host . '/coupon/' . $store['slug']) . "</loc>\n";
                $sitemap .= "        <changefreq>weekly</changefreq>\n";
                $sitemap .= "        <priority>0.7</priority>\n";
                $sitemap .= "    </url>\n";
            }

            // Blog Post pages
            $blogPosts = $postModel->getAllForAdmin(null, 'published');
            foreach ($blogPosts as $post) {
                $sitemap .= "    <url>\n";
                $sitemap .= "        <loc>" . htmlspecialchars($host . '/blog/' . $post['slug']) . "</loc>\n";
                $sitemap .= "        <lastmod>" . date('Y-m-d', strtotime($post['updated_at'] ?? $post['published_at'] ?? $post['created_at'])) . "</lastmod>\n";
                $sitemap .= "        <changefreq>monthly</changefreq>\n";
                $sitemap .= "        <priority>0.6</priority>\n";
                $sitemap .= "    </url>\n";
            }

            $sitemap .= "</urlset>\n";

            file_put_contents(ROOT_PATH . '/public/sitemap.xml', $sitemap);

        } catch (Exception $e) {
            error_log("SEO Regeneration error: " . $e->getMessage());
        }
    }

    // ========================
    // HELPERS
    // ========================

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
            'ý'=>'y','ỳ'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
            'đ'=>'d'
        ];

        $text = strtr($text, $replacements);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/\s+/', '-', $text);
        return trim($text, '-');
    }
}
