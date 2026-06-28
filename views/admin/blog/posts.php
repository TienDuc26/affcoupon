<div class="content-card">

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3>Quản lý Bài viết Blog</h3>

        <div class="header-actions">
            <a href="/admin/blogposts" class="btn btn-outline-secondary">
                <i class="fa fa-sync"></i> Làm mới
            </a>
            <a href="/admin/addblogpost" class="btn btn-success">
                <i class="fa fa-plus"></i> Viết bài mới
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <form method="GET" action="/admin/blogposts">
        <div class="store-filter-bar row g-3 mb-4 align-items-center">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control store-filter-input" placeholder="Tìm kiếm theo tiêu đề..." value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
            </div>

            <div class="col-md-3">
                <select name="categoryId" class="form-select form-control store-filter-input">
                    <option value="">Tất cả danh mục</option>
                    <?php if (!empty($blogCategories)): ?>
                        <?php foreach ($blogCategories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo (isset($selectedCategoryId) && $selectedCategoryId == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select form-control store-filter-input">
                    <option value="">Tất cả trạng thái</option>
                    <option value="published" <?php echo (isset($status) && $status === 'published') ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo (isset($status) && $status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100 store-filter-btn" style="background-color: #16A516 !important; border: none;">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- POSTS TABLE -->
    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>Tiêu đề bài viết</th>
                    <th>Thumbnail</th>
                    <th>Trạng thái</th>
                    <th>Danh mục</th>
                    <th>Tác giả</th>
                    <th>Lượt xem</th>
                    <th>Ngày đăng</th>
                    <th width="160">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $item): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                            <td>
                                <?php if (!empty($item['thumbnail'])): ?>
                                    <img class="store-logo" src="<?php echo htmlspecialchars($item['thumbnail']); ?>" alt="Thumbnail" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.85rem;">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['status'] === 'published'): ?>
                                    <span class="badge bg-success text-white">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-white">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['category_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item['author_name'] ?? 'N/A'); ?></td>
                            <td><?php echo $item['view_count']; ?></td>
                            <td><?php echo $item['published_at'] ? date('d/m/Y H:i', strtotime($item['published_at'])) : date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="/admin/editblogpost?id=<?php echo $item['id']; ?>" class="action-btn btn-edit mr-2 text-warning" title="Chỉnh sửa">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="/blog/<?php echo htmlspecialchars($item['slug']); ?>" class="action-btn btn-view mr-2 text-primary" title="Xem chi tiết" target="_blank">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="/admin/deleteblogpost?id=<?php echo $item['id']; ?>" class="action-btn btn-delete text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy bài viết nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
