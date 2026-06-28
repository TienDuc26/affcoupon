<div class="content-card">

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3>Quản lý store</h3>

        <div class="header-actions">
            <a href="/admin/dashboardadmin" class="btn btn-outline-secondary">
                <i class="fa fa-sync"></i> Làm mới
            </a>
            <a href="/admin/addstore" class="btn btn-success">
                <i class="fa fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <form method="GET" action="/admin/dashboardadmin">
        <div class="store-filter-bar row g-3 mb-4 align-items-center">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control store-filter-input" placeholder="Tìm kiếm theo tên..." value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
            </div>

            <div class="col-md-3">
                <select name="categoryId" class="form-select form-control store-filter-input">
                    <option value="">Tất cả danh mục</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo (isset($selectedCategoryId) && $selectedCategoryId == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="isActive" class="form-select form-control store-filter-input">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" <?php echo (isset($isActive) && $isActive === 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo (isset($isActive) && $isActive === 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100 store-filter-btn">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- STORES TABLE -->
    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th width="40"><input type="checkbox" id="check-all"></th>
                    <th>Tên Store</th>
                    <th>Logo</th>
                    <th>Trạng thái</th>
                    <th>Danh mục</th>
                    <th>Lượt xem</th>
                    <th>ID</th>
                    <th>Ngày đăng</th>
                    <th width="160">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($stores)): ?>
                    <?php foreach ($stores as $item): ?>
                        <tr>
                            <td><input type="checkbox" class="store-checkbox" value="<?php echo $item['id']; ?>"></td>
                            <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                            <td>
                                <?php if (!empty($item['thumbnail_url'])): ?>
                                    <img class="store-logo" src="<?php echo htmlspecialchars($item['thumbnail_url']); ?>" alt="Logo" style="width: 50px; height: 50px; object-fit: contain;">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['category_name'] ?? 'N/A'); ?></td>
                            <td><?php echo $item['view_count']; ?></td>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="/admin/detailstore?id=<?php echo $item['id']; ?>" class="action-btn btn-view mr-1 text-primary" title="Xem chi tiết">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="/admin/editstore?id=<?php echo $item['id']; ?>" class="action-btn btn-edit mr-1 text-warning" title="Chỉnh sửa">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="/coupon/<?php echo htmlspecialchars($item['slug']); ?>" class="action-btn btn-coupon mr-1 text-info" title="Xem coupon detail" target="_blank">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                    <a href="/admin/deletestore?id=<?php echo $item['id']; ?>" class="action-btn btn-delete text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa store này? Tất cả FAQs và Vouchers liên quan sẽ bị xóa.')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Không tìm thấy store nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
