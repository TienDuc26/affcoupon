<!-- BLOG HERO BANNER -->
<div class="bg-success text-white py-5 mb-5 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;">
    <div class="container text-center py-3">
        <h1 class="display-4 fw-bold text-white mb-2">Peak Vouch Blog</h1>
        <p class="lead text-white-50 max-width-600 mx-auto">Chia sẻ kinh nghiệm mua sắm thông thái, mã giảm giá hời và tin tức công nghệ mới nhất.</p>
        
        <!-- Search bar inside hero -->
        <div class="mt-4 d-flex justify-content-center">
            <form class="d-flex w-100" action="/blog" method="GET" style="max-width: 500px;">
                <?php if ($SelectedCategoryId): ?>
                    <input type="hidden" name="categoryId" value="<?php echo $SelectedCategoryId; ?>" />
                <?php endif; ?>
                <?php if ($SelectedTagId): ?>
                    <input type="hidden" name="tagId" value="<?php echo $SelectedTagId; ?>" />
                <?php endif; ?>
                <div class="input-group input-group-premium shadow-sm">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm bài viết..." value="<?php echo htmlspecialchars($Keyword ?? ''); ?>">
                    <button class="btn btn-success" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- MAIN BLOG POSTS LIST -->
    <div class="col-lg-8">
        <?php if (!empty($Keyword)): ?>
            <div class="alert alert-light border mb-4 rounded-3 d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-info-circle text-success me-1"></i> Kết quả tìm kiếm cho từ khóa: <strong>"<?php echo htmlspecialchars($Keyword); ?>"</strong>
                </span>
                <a href="/blog" class="text-success text-decoration-none fw-bold small">Xóa bộ lọc</a>
            </div>
        <?php endif; ?>

        <?php if ($SelectedCategoryId || $SelectedTagId): ?>
            <div class="alert alert-light border mb-4 rounded-3 d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-filter text-success me-1"></i> Đang hiển thị bài viết theo bộ lọc
                </span>
                <a href="/blog" class="text-success text-decoration-none fw-bold small">Xóa bộ lọc</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (!empty($Posts)): ?>
                <?php foreach ($Posts as $post): ?>
                    <div class="col-md-6">
                        <div class="card blog-card h-100">
                            <!-- Image wrapper -->
                            <div class="blog-card-img-wrapper">
                                <?php if (!empty($post['category_name'])): ?>
                                    <span class="blog-card-badge"><?php echo htmlspecialchars($post['category_name']); ?></span>
                                <?php endif; ?>
                                <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="w-100 h-100 d-block">
                                    <img src="<?php echo htmlspecialchars($post['thumbnail'] ?: '/img/banner.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-card-img" />
                                </a>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="blog-meta mb-2 small text-muted d-flex gap-3">
                                    <span><i class="far fa-calendar-alt text-success me-1"></i> <?php echo date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
                                    <span><i class="far fa-clock text-success me-1"></i> <?php echo htmlspecialchars($post['reading_time'] ?: '5 phút đọc'); ?></span>
                                </div>

                                <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="text-decoration-none">
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px;" title="<?php echo htmlspecialchars($post['title']); ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </h5>
                                </a>

                                <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 54px; line-height: 1.6;">
                                    <?php echo htmlspecialchars($post['summary'] ?? ''); ?>
                                </p>

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">
                                        <i class="far fa-user text-success me-1"></i> <?php echo htmlspecialchars($post['author_name'] ?: 'Peak Vouch'); ?>
                                    </span>
                                    <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="text-success fw-bold text-decoration-none small">
                                        Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fa-regular fa-folder-open"></i>
                        <h4>Không có bài viết nào</h4>
                        <p class="text-muted">Chưa tìm thấy bài viết phù hợp với các tiêu chí bộ lọc của bạn.</p>
                        <a href="/blog" class="btn btn-success px-4 btn-sm">Xem tất cả bài viết</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($TotalPages > 1): ?>
            <div class="pagination-premium">
                <?php 
                    $prevUrl = $CurrentPage > 1 ? "/blog?page=" . ($CurrentPage - 1) . ($SelectedCategoryId ? "&categoryId=" . $SelectedCategoryId : '') . ($SelectedTagId ? "&tagId=" . $SelectedTagId : '') . ($Keyword ? "&keyword=" . urlencode($Keyword) : '') : "#";
                ?>
                <a href="<?php echo $prevUrl; ?>" class="<?php echo $CurrentPage > 1 ? '' : 'disabled'; ?>" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                
                <?php for ($i = 1; $i <= $TotalPages; $i++): ?>
                    <?php 
                        $pageUrl = "/blog?page=" . $i . ($SelectedCategoryId ? "&categoryId=" . $SelectedCategoryId : '') . ($SelectedTagId ? "&tagId=" . $SelectedTagId : '') . ($Keyword ? "&keyword=" . urlencode($Keyword) : '');
                    ?>
                    <a href="<?php echo $pageUrl; ?>" class="<?php echo ($i == $CurrentPage ? "active" : ""); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php 
                    $nextUrl = $CurrentPage < $TotalPages ? "/blog?page=" . ($CurrentPage + 1) . ($SelectedCategoryId ? "&categoryId=" . $SelectedCategoryId : '') . ($SelectedTagId ? "&tagId=" . $SelectedTagId : '') . ($Keyword ? "&keyword=" . urlencode($Keyword) : '') : "#";
                ?>
                <a href="<?php echo $nextUrl; ?>" class="<?php echo $CurrentPage < $TotalPages ? '' : 'disabled'; ?>" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- SIDEBAR WIDGETS -->
    <div class="col-lg-4">
        <!-- CATEGORIES -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Danh mục Blog</h5>
            <div class="list-group list-group-flush">
                <a href="/blog" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 py-2 <?php echo !$SelectedCategoryId ? 'text-success fw-bold' : 'text-secondary'; ?>">
                    Tất cả bài viết
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
                <?php if (!empty($BlogCategories)): ?>
                    <?php foreach ($BlogCategories as $cat): ?>
                        <a href="/blog?categoryId=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 py-2 <?php echo $SelectedCategoryId == $cat['id'] ? 'text-success fw-bold' : 'text-secondary'; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                            <i class="fas fa-chevron-right small text-muted" style="font-size: 0.7rem;"></i>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- TAG CLOUD -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Tags phổ biến</h5>
            <div class="d-flex flex-wrap gap-2 pt-1">
                <?php if (!empty($AllTags)): ?>
                    <?php foreach ($AllTags as $tag): ?>
                        <a href="/blog?tagId=<?php echo $tag['id']; ?>" class="btn btn-pill btn-sm px-3 py-2 <?php echo $SelectedTagId == $tag['id'] ? 'btn-success' : 'btn-light'; ?>">
                            #<?php echo htmlspecialchars($tag['name']); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted small">Chưa có tag nào</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

