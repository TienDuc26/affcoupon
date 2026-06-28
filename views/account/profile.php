<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="row align-items-center g-4">
                <div class="col-md-auto text-center">
                    <div class="position-relative d-inline-block">
                        <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?d=mp&s=150'); ?>" 
                             alt="Avatar" class="rounded-circle border border-3 border-success-subtle shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <button class="btn btn-sm btn-success rounded-circle position-absolute bottom-0 end-0 shadow-sm" 
                                onclick="document.getElementById('avatar-upload-input').click();"
                                data-bs-toggle="tooltip" title="Thay đổi ảnh đại diện">
                            <i class="fas fa-camera"></i>
                        </button>
                        <form id="avatar-upload-form" action="/profile/avatar" method="POST" enctype="multipart/form-data" class="d-none">
                            <input type="file" id="avatar-upload-input" name="Avatar" accept="image/*" onchange="document.getElementById('avatar-upload-form').submit();">
                        </form>
                    </div>
                </div>
                <div class="col-md text-center text-md-start">
                    <span class="badge bg-success-subtle text-success mb-2 fw-bold">Thành viên</span>
                    <h2 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($user['fullname'] ?: $user['username']); ?></h2>
                    <p class="text-secondary mb-3"><i class="far fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 text-secondary small">
                        <span><i class="far fa-calendar-alt me-2"></i>Tham gia từ: <strong><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></strong></span>
                        <span><i class="far fa-heart me-2 text-danger"></i><strong><?php echo $favCount; ?></strong> yêu thích</span>
                        <span><i class="far fa-bookmark me-2 text-success"></i><strong><?php echo $bookCount; ?></strong> đã lưu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert messages -->
        <?php if ($successMsg): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($successMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($errorMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Dashboard Body -->
        <div class="row g-4">
            <!-- Sidebar / Tabs Navigation -->
            <div class="col-lg-3 col-md-12">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <!-- Desktop Sidebar Menu -->
                    <div class="d-none d-lg-flex flex-column gap-2">
                        <a href="/profile?tab=profile" class="btn text-start py-3 px-4 rounded-3 d-flex align-items-center gap-3 <?php echo $activeTab === 'profile' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-user-circle fa-lg"></i> Hồ sơ cá nhân
                        </a>
                        <a href="/profile?tab=favorites" class="btn text-start py-3 px-4 rounded-3 d-flex align-items-center gap-3 <?php echo $activeTab === 'favorites' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-heart fa-lg"></i> Coupon yêu thích
                        </a>
                        <a href="/profile?tab=bookmarks" class="btn text-start py-3 px-4 rounded-3 d-flex align-items-center gap-3 <?php echo $activeTab === 'bookmarks' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-bookmark fa-lg"></i> Coupon đã lưu
                        </a>
                        <a href="/profile?tab=password" class="btn text-start py-3 px-4 rounded-3 d-flex align-items-center gap-3 <?php echo $activeTab === 'password' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-key fa-lg"></i> Đổi mật khẩu
                        </a>
                        <hr class="my-2">
                        <a href="/account/logout" class="btn btn-light text-danger text-start py-3 px-4 rounded-3 d-flex align-items-center gap-3">
                            <i class="fas fa-sign-out-alt fa-lg"></i> Đăng xuất
                        </a>
                    </div>

                    <!-- Mobile/Tablet Tab Menu -->
                    <div class="d-flex d-lg-none overflow-auto flex-nowrap gap-2 pb-2">
                        <a href="/profile?tab=profile" class="btn py-2 px-3 rounded-pill text-nowrap d-flex align-items-center gap-2 <?php echo $activeTab === 'profile' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-user-circle"></i> Hồ sơ
                        </a>
                        <a href="/profile?tab=favorites" class="btn py-2 px-3 rounded-pill text-nowrap d-flex align-items-center gap-2 <?php echo $activeTab === 'favorites' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-heart"></i> Yêu thích (<?php echo $favCount; ?>)
                        </a>
                        <a href="/profile?tab=bookmarks" class="btn py-2 px-3 rounded-pill text-nowrap d-flex align-items-center gap-2 <?php echo $activeTab === 'bookmarks' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-bookmark"></i> Đã lưu (<?php echo $bookCount; ?>)
                        </a>
                        <a href="/profile?tab=password" class="btn py-2 px-3 rounded-pill text-nowrap d-flex align-items-center gap-2 <?php echo $activeTab === 'password' ? 'btn-success fw-bold' : 'btn-light text-secondary'; ?>">
                            <i class="fas fa-key"></i> Bảo mật
                        </a>
                        <a href="/account/logout" class="btn btn-light text-danger py-2 px-3 rounded-pill text-nowrap d-flex align-items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9 col-md-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white min-vh-50">
                    
                    <!-- TAB 1: PROFILE INFO -->
                    <?php if ($activeTab === 'profile'): ?>
                        <!-- Stats Grid -->
                        <div class="row g-3 mb-5">
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center h-100">
                                    <i class="fas fa-heart text-danger fa-2x mb-2"></i>
                                    <h4 class="fw-bold mb-0"><?php echo $favCount; ?></h4>
                                    <span class="text-secondary small">Yêu thích</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center h-100">
                                    <i class="fas fa-bookmark text-success fa-2x mb-2"></i>
                                    <h4 class="fw-bold mb-0"><?php echo $bookCount; ?></h4>
                                    <span class="text-secondary small">Đã lưu</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center h-100">
                                    <i class="fas fa-gift text-warning fa-2x mb-2"></i>
                                    <h4 class="fw-bold mb-0"><?php echo ($favCount + $bookCount) * 2; ?></h4>
                                    <span class="text-secondary small">Đã nhận</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center h-100">
                                    <i class="far fa-id-card text-primary fa-2x mb-2"></i>
                                    <h5 class="fw-bold mb-1" style="font-size: 0.95rem;"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></h5>
                                    <span class="text-secondary small">Thành viên từ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Form -->
                        <h4 class="fw-bold text-dark mb-4 border-bottom pb-2">Thông tin cá nhân</h4>
                        <form action="/profile/update" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="FullName" class="form-control py-2 rounded-3" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">Tên đăng nhập (Username)</label>
                                    <input type="text" class="form-control py-2 rounded-3 bg-light" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="Email" class="form-control py-2 rounded-3" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">Số điện thoại</label>
                                    <input type="text" name="Phone" class="form-control py-2 rounded-3" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Ví dụ: 0987654321">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">Ngày sinh</label>
                                    <input type="date" name="Birthday" class="form-control py-2 rounded-3" value="<?php echo htmlspecialchars($user['birthday'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">Giới tính</label>
                                    <select name="Gender" class="form-select py-2 rounded-3">
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Nam</option>
                                        <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Nữ</option>
                                        <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Khác</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success px-5 py-2.5 rounded-3 mt-4 fw-bold">
                                <i class="fas fa-save me-2"></i> Cập nhật thông tin
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- TAB 2: FAVORITE COUPONS -->
                    <?php if ($activeTab === 'favorites'): ?>
                        <h4 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="fas fa-heart text-danger me-2"></i> Coupon yêu thích</h4>
                        
                        <?php if (!empty($favoriteCoupons)): ?>
                            <div class="row g-4">
                                <?php foreach ($favoriteCoupons as $item): ?>
                                    <div class="col-md-6 col-12">
                                        <div class="deal-card-premium">
                                            <div class="deal-img">
                                                <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>">
                                                    <img src="<?php echo htmlspecialchars($item['thumbnail_url'] ?? '/img/Logo.jpg'); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                                </a>
                                                <span class="discount-badge">XÁC THỰC</span>
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
                                                <p class="text-muted mb-3" style="font-size: 0.85rem;"><?php echo htmlspecialchars($item['description'] ?? 'Nhấp xem danh sách mã giảm giá của cửa hàng.'); ?></p>
                                                
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
                            <?php if ($favTotalPages > 1): ?>
                                <div class="pagination-premium mt-5">
                                    <?php 
                                        $prevUrl = $favPage > 1 ? "/profile?tab=favorites&fav_page=" . ($favPage - 1) : "#";
                                        $nextUrl = $favPage < $favTotalPages ? "/profile?tab=favorites&fav_page=" . ($favPage + 1) : "#";
                                    ?>
                                    <a href="<?php echo $prevUrl; ?>" class="<?php echo $favPage > 1 ? '' : 'disabled'; ?>">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                    <?php for ($i = 1; $i <= $favTotalPages; $i++): ?>
                                        <a href="/profile?tab=favorites&fav_page=<?php echo $i; ?>" class="<?php echo $i === $favPage ? 'active' : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    <a href="<?php echo $nextUrl; ?>" class="<?php echo $favPage < $favTotalPages ? '' : 'disabled'; ?>">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="far fa-heart text-muted"></i>
                                <h4 class="fw-bold mt-3">Bạn chưa có coupon yêu thích.</h4>
                                <p class="text-muted mb-4">Nhấn nút trái tim trên các mã ưu đãi ở trang chủ để thêm vào đây.</p>
                                <a href="/" class="btn btn-success btn-pill px-4">Khám phá ngay</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- TAB 3: BOOKMARKED COUPONS -->
                    <?php if ($activeTab === 'bookmarks'): ?>
                        <h4 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="fas fa-bookmark text-success me-2"></i> Coupon đã lưu</h4>
                        
                        <?php if (!empty($bookmarkCoupons)): ?>
                            <div class="row g-4">
                                <?php foreach ($bookmarkCoupons as $item): ?>
                                    <div class="col-md-6 col-12">
                                        <div class="deal-card-premium">
                                            <div class="deal-img">
                                                <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>">
                                                    <img src="<?php echo htmlspecialchars($item['thumbnail_url'] ?? '/img/Logo.jpg'); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                                </a>
                                                <span class="discount-badge">XÁC THỰC</span>
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
                                                <p class="text-muted mb-3" style="font-size: 0.85rem;"><?php echo htmlspecialchars($item['description'] ?? 'Nhấp xem danh sách mã giảm giá của cửa hàng.'); ?></p>
                                                
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
                            <?php if ($bookTotalPages > 1): ?>
                                <div class="pagination-premium mt-5">
                                    <?php 
                                        $prevUrl = $bookPage > 1 ? "/profile?tab=bookmarks&book_page=" . ($bookPage - 1) : "#";
                                        $nextUrl = $bookPage < $bookTotalPages ? "/profile?tab=bookmarks&book_page=" . ($bookPage + 1) : "#";
                                    ?>
                                    <a href="<?php echo $prevUrl; ?>" class="<?php echo $bookPage > 1 ? '' : 'disabled'; ?>">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                    <?php for ($i = 1; $i <= $bookTotalPages; $i++): ?>
                                        <a href="/profile?tab=bookmarks&book_page=<?php echo $i; ?>" class="<?php echo $i === $bookPage ? 'active' : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    <a href="<?php echo $nextUrl; ?>" class="<?php echo $bookPage < $bookTotalPages ? '' : 'disabled'; ?>">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="far fa-bookmark text-muted"></i>
                                <h4 class="fw-bold mt-3">Bạn chưa lưu coupon nào.</h4>
                                <p class="text-muted mb-4">Lưu các mã ưu đãi quan tâm để dễ dàng sử dụng lại sau này.</p>
                                <a href="/" class="btn btn-success btn-pill px-4">Khám phá ngay</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- TAB 4: CHANGE PASSWORD -->
                    <?php if ($activeTab === 'password'): ?>
                        <h4 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="fas fa-key fa-lg me-2"></i> Đổi mật khẩu</h4>
                        <form action="/profile/password" method="POST" style="max-width: 500px;">
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Mật khẩu hiện tại</label>
                                <input type="password" name="OldPassword" class="form-control py-2 rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Mật khẩu mới</label>
                                <input type="password" name="NewPassword" class="form-control py-2 rounded-3" required>
                                <div class="form-text text-muted small">Độ dài mật khẩu tối thiểu là 6 ký tự.</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-secondary small fw-bold">Xác nhận mật khẩu mới</label>
                                <input type="password" name="ConfirmPassword" class="form-control py-2 rounded-3" required>
                            </div>
                            <button type="submit" class="btn btn-success px-5 py-2.5 rounded-3 fw-bold">
                                <i class="fas fa-key me-2"></i> Đổi mật khẩu
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
