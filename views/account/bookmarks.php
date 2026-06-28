<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="mb-5">
            <span class="badge bg-success-subtle text-success mb-2 fw-bold">Tài khoản</span>
            <h1 class="fw-bold text-dark"><i class="fa-solid fa-bookmark text-success me-2"></i> Coupon đã lưu của bạn</h1>
            <p class="text-muted">Danh sách các thương hiệu và mã giảm giá bạn đã lưu trữ để sử dụng sau.</p>
        </div>

        <?php if (!empty($Coupons)): ?>
            <div class="row g-4">
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
            </div>

            <!-- Pagination -->
            <?php if ($TotalPages > 1): ?>
                <div class="pagination-premium mt-5">
                    <?php 
                        $prevUrl = $CurrentPage > 1 ? "/account/bookmarks?page=" . ($CurrentPage - 1) : "#";
                        $nextUrl = $CurrentPage < $TotalPages ? "/account/bookmarks?page=" . ($CurrentPage + 1) : "#";
                    ?>
                    <a href="<?php echo $prevUrl; ?>" class="<?php echo $CurrentPage > 1 ? '' : 'disabled'; ?>" aria-label="Previous">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    
                    <?php for ($i = 1; $i <= $TotalPages; $i++): ?>
                        <a href="/account/bookmarks?page=<?php echo $i; ?>" class="<?php echo $i === $CurrentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <a href="<?php echo $nextUrl; ?>" class="<?php echo $CurrentPage < $TotalPages ? '' : 'disabled'; ?>" aria-label="Next">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state bg-white">
                <i class="fa-regular fa-bookmark text-muted"></i>
                <h4 class="fw-bold mt-3">Bạn chưa lưu coupon nào.</h4>
                <p class="text-muted mb-4">Lưu trữ mã giảm giá bạn quan tâm để dễ dàng tìm kiếm khi cần mua sắm.</p>
                <a href="/" class="btn btn-success btn-pill px-4">Khám phá coupon</a>
            </div>
        <?php endif; ?>
    </div>
</section>
