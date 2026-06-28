<?php
// Icon mapping helper for categories
if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($slug) {
        $slug = strtolower($slug);
        if (strpos($slug, 'ai') !== false || strpos($slug, 'robot') !== false) return 'fa-robot text-success';
        if (strpos($slug, 'hosting') !== false || strpos($slug, 'server') !== false) return 'fa-server text-primary';
        if (strpos($slug, 'vpn') !== false || strpos($slug, 'shield') !== false) return 'fa-shield-halved text-danger';
        if (strpos($slug, 'edu') !== false || strpos($slug, 'learn') !== false || strpos($slug, 'hoc') !== false) return 'fa-graduation-cap text-warning';
        if (strpos($slug, 'software') !== false || strpos($slug, 'code') !== false || strpos($slug, 'lap') !== false) return 'fa-laptop-code text-info';
        if (strpos($slug, 'shop') !== false || strpos($slug, 'cart') !== false || strpos($slug, 'mua') !== false) return 'fa-cart-shopping text-success';
        if (strpos($slug, 'travel') !== false || strpos($slug, 'plane') !== false || strpos($slug, 'bay') !== false) return 'fa-plane-departure text-primary';
        if (strpos($slug, 'finance') !== false || strpos($slug, 'money') !== false || strpos($slug, 'tien') !== false) return 'fa-money-bill-trend-up text-success';
        return 'fa-tags text-success'; // default
    }
}
?>

<!-- ========================================= -->
<!-- HERO SECTION -->
<!-- ========================================= -->
<section class="bg-white py-5 mb-5 border-bottom">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-success-subtle text-success mb-3 fw-bold">
                    🔥 Hơn 50,000+ người dùng tin tưởng sử dụng
                </span>
                
                <h1 class="display-4 fw-bold mb-3">
                    Khám phá <span class="text-success">mã giảm giá</span> và khuyến mãi đỉnh nhất
                </h1>
                
                <p class="lead text-secondary mb-4">
                    Tìm kiếm các mã giảm giá được xác thực hàng ngày, ưu đãi hosting, VPN, công cụ AI, khóa học và các sản phẩm dịch vụ số khác.
                </p>
                
                <form action="/" method="GET" class="mb-4">
                    <div class="input-group input-group-premium shadow">
                        <input type="text" name="keyword" class="form-control" placeholder="Tìm deals, hosting, phần mềm, coupons..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                        <button class="btn btn-success" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
                
                <div class="trending-tags mb-4 small">
                    <span class="text-secondary me-2">Từ khóa hot:</span>
                    <a href="/?keyword=ChatGPT" class="badge bg-light text-dark text-decoration-none">ChatGPT</a>
                    <a href="/?keyword=Canva" class="badge bg-light text-dark text-decoration-none">Canva</a>
                    <a href="/?keyword=Hostinger" class="badge bg-light text-dark text-decoration-none">Hostinger</a>
                    <a href="/?keyword=NordVPN" class="badge bg-light text-dark text-decoration-none">NordVPN</a>
                </div>
                
                <div class="d-flex gap-5 mt-5 border-top pt-4">
                    <div>
                        <h3 class="fw-bold mb-0">20K+</h3>
                        <p class="text-secondary mb-0">Coupons</p>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">500+</h3>
                        <p class="text-secondary mb-0">Thương hiệu</p>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">98%</h3>
                        <p class="text-secondary mb-0">Thành công</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-4 shadow" alt="Hero Banner">
            </div>
        </div>
    </div>
</section>

<!-- ========================================= -->
<!-- POPULAR CATEGORIES -->
<!-- ========================================= -->
<section class="py-0 mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-success fw-bold small text-uppercase">Danh mục phổ biến</span>
                <h2 class="fw-bold mt-1 mb-2">Tìm deals theo danh mục</h2>
                <p class="text-muted mb-0">Tìm các ưu đãi tốt nhất phù hợp với nhu cầu mua sắm của bạn.</p>
            </div>
            <a href="/" class="btn btn-outline-success btn-pill btn-sm px-4">Xem tất cả</a>
        </div>
        
        <?php if (!empty($Categories)): ?>
            <div class="row g-4">
                <?php foreach (array_slice($Categories, 0, 8) as $cat): ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="/?categoryId=<?php echo $cat['id']; ?>" class="text-decoration-none">
                            <div class="category-card">
                                <i class="fa-solid <?php echo getCategoryIcon($cat['slug']); ?> fa-3x mb-3"></i>
                                <h5><?php echo htmlspecialchars($cat['name']); ?></h5>
                                <p class="text-muted small mb-0">Khám phá ngay</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========================================= -->
<!-- LATEST DEALS -->
<!-- ========================================= -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="text-success fw-bold small text-uppercase">Ưu đãi mới nhất</span>
                <h2 class="fw-bold mt-1 mb-2">Mã giảm giá & Hot Deals hôm nay</h2>
                <p class="text-muted mb-0">Các chương trình ưu đãi được cập nhật liên tục mỗi giờ.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Trang <?php echo $CurrentPage; ?> / <?php echo $TotalPages; ?></span>
            </div>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($Coupons)): ?>
                <?php foreach ($Coupons as $item): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="deal-card-premium">
                            <div class="deal-img">
                                <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>">
                                    <img src="<?php echo htmlspecialchars($item['thumbnail_url'] ?? '/img/Logo.jpg'); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                </a>
                                <span class="discount-badge">XÁC THỰC</span>
                                <?php if ($item['is_featured']): ?>
                                    <span class="hot-badge">NỔI BẬT</span>
                                <?php endif; ?>
                            </div>
                            <div class="store-logo">
                                <span class="fw-bold text-success text-uppercase" style="font-size: 1.15rem;">
                                    <?php echo htmlspecialchars(substr($item['title'], 0, 1)); ?>
                                </span>
                            </div>
                            <div class="deal-content">
                                <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>" class="text-decoration-none">
                                    <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($item['title']); ?></h5>
                                </a>
                                <p class="text-muted mb-3"><?php echo htmlspecialchars($item['description'] ?? 'Nhấp xem danh sách mã giảm giá và khuyến mãi cực hời của store.'); ?></p>
                                
                                <div class="rating mb-3">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <span class="text-secondary ms-1">(<?php echo ($item['view_count'] * 2) % 150 + 10; ?>)</span>
                                </div>
                                
                                <div class="deal-meta">
                                    <span><i class="fa-solid fa-eye"></i> <?php echo $item['view_count']; ?></span>
                                    <span><i class="fa-solid fa-clock"></i> <?php echo date('d/m/Y', strtotime($item['created_at'])); ?></span>
                                </div>
                                
                                <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>" class="get-btn mt-auto text-decoration-none">
                                    Nhận Coupon
                                </a>
                                
                                 <?php include ROOT_PATH . '/views/shared/action_bar.php'; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fa-regular fa-folder-open"></i>
                        <h4>Không tìm thấy mã giảm giá phù hợp</h4>
                        <p class="text-muted mb-4">Vui lòng thử lại với từ khóa tìm kiếm khác hoặc chuyển danh mục.</p>
                        <a href="/" class="btn btn-success px-4">Xem tất cả Deals</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($TotalPages > 1): ?>
            <div class="pagination-premium">
                <?php 
                    $prevUrl = $CurrentPage > 1 ? "/?page=" . ($CurrentPage - 1) . ($SelectedCategoryId ? "&categoryId=" . $SelectedCategoryId : '') . (isset($_GET['keyword']) ? '&keyword=' . urlencode($_GET['keyword']) : '') : "#";
                ?>
                <a href="<?php echo $prevUrl; ?>" class="<?php echo $CurrentPage > 1 ? '' : 'disabled'; ?>" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                
                <?php for ($i = 1; $i <= $TotalPages; $i++): ?>
                    <?php 
                        $pageUrl = "/?page=" . $i . ($SelectedCategoryId ? "&categoryId=" . $SelectedCategoryId : '') . (isset($_GET['keyword']) ? '&keyword=' . urlencode($_GET['keyword']) : '');
                    ?>
                    <a href="<?php echo $pageUrl; ?>" class="<?php echo ($i == $CurrentPage ? "active" : ""); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php 
                    $nextUrl = $CurrentPage < $TotalPages ? "/?page=" . ($CurrentPage + 1) . ($SelectedCategoryId ? "&categoryId=" . $SelectedCategoryId : '') . (isset($_GET['keyword']) ? '&keyword=' . urlencode($_GET['keyword']) : '') : "#";
                ?>
                <a href="<?php echo $nextUrl; ?>" class="<?php echo $CurrentPage < $TotalPages ? '' : 'disabled'; ?>" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========================================= -->
<!-- WHY CHOOSE US -->
<!-- ========================================= -->
<section class="bg-white border-top border-bottom py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-success fw-bold small text-uppercase">Về Peak Vouch</span>
            <h2 class="fw-bold mt-2">Tại sao chọn Peak Vouch?</h2>
            <p class="text-muted">Nơi mang đến cho bạn trải nghiệm săn mã giảm giá chất lượng và tin cậy.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                    <div class="mb-3 text-success">
                        <i class="fa-solid fa-circle-check fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Coupons xác thực</h5>
                    <p class="text-muted mb-0">Tất cả các coupon được kiểm tra thủ công kỹ lưỡng trước khi xuất bản lên hệ thống.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                    <div class="mb-3 text-warning">
                        <i class="fa-solid fa-bolt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Cập nhật nhanh chóng</h5>
                    <p class="text-muted mb-0">Nhận các khuyến mãi, deal độc quyền của các thương hiệu hàng giờ, hàng ngày.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                    <div class="mb-3 text-primary">
                        <i class="fa-solid fa-lock fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Liên kết an toàn</h5>
                    <p class="text-muted mb-0">Nói không với tin nhắn rác, quảng cáo độc hại. Chỉ chuyển tiếp tới trang chính thức của đối tác.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                    <div class="mb-3 text-danger">
                        <i class="fa-solid fa-headset fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Hỗ trợ 24/7</h5>
                    <p class="text-muted mb-0">Đội ngũ kỹ thuật viên luôn sẵn sàng giải đáp thắc mắc và hỗ trợ người dùng tìm mã giảm giá.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================= -->
<!-- DEALS OF THE DAY (FEATURED) -->
<!-- ========================================= -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="text-success fw-bold small text-uppercase">Hot deals trong ngày</span>
                <h2 class="fw-bold mt-1 mb-2">Ưu đãi nổi bật hôm nay</h2>
                <p class="text-muted mb-0">Đừng bỏ lỡ các ưu đãi đặc biệt có lượt xem khủng nhất tuần này.</p>
            </div>
            <a href="#" class="btn btn-success btn-pill px-4">Xem thêm</a>
        </div>
        
        <div class="row g-4">
            <?php 
                $featuredCount = 0;
                if (!empty($Coupons)) {
                    foreach ($Coupons as $item) {
                        if ($item['is_featured']) {
                            $featuredCount++;
            ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="deal-card-premium">
                                    <div class="deal-img">
                                        <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>">
                                            <img src="<?php echo htmlspecialchars($item['thumbnail_url'] ?? '/img/Logo.jpg'); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                        </a>
                                        <span class="discount-badge bg-danger">GIẢM SỐC</span>
                                        <span class="hot-badge"><i class="fa-solid fa-fire"></i> HOT</span>
                                    </div>
                                    <div class="store-logo">
                                        <span class="fw-bold text-success text-uppercase" style="font-size: 1.15rem;">
                                            <?php echo htmlspecialchars(substr($item['title'], 0, 1)); ?>
                                        </span>
                                    </div>
                                    <div class="deal-content">
                                        <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>" class="text-decoration-none">
                                            <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($item['title']); ?></h5>
                                        </a>
                                        <p class="text-muted mb-3"><?php echo htmlspecialchars($item['description'] ?? 'Tìm kiếm mã giảm giá hấp dẫn nhất dành riêng cho thương hiệu này.'); ?></p>
                                        
                                        <div class="rating mb-3">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <span class="text-secondary ms-1">(<?php echo $item['view_count'] + 15; ?>)</span>
                                        </div>
                                        
                                        <div class="deal-meta">
                                            <span><i class="fa-solid fa-eye"></i> <?php echo $item['view_count']; ?></span>
                                            <span><i class="fa-solid fa-fire"></i> Hot Deal</span>
                                        </div>
                                        
                                        <a href="<?php echo htmlspecialchars($item['affiliate_url']); ?>" target="_blank" class="get-btn mt-auto text-decoration-none bg-dark border-dark">
                                            Mua Ngay
                                        </a>
                                        
                                        <?php include ROOT_PATH . '/views/shared/action_bar.php'; ?>
                                    </div>
                                </div>
                            </div>
            <?php
                        }
                    }
                }
                if ($featuredCount === 0):
            ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fa-regular fa-face-frown"></i>
                        <h4>Không tìm thấy ưu đãi nổi bật</h4>
                        <p class="text-muted">Các ưu đãi nổi bật sẽ sớm quay trở lại. Hãy theo dõi trang của chúng tôi.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

