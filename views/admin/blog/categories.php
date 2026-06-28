<div class="content-card">

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3>Quản lý Danh mục Blog</h3>

        <div class="header-actions">
            <a href="/admin/blogcategories" class="btn btn-outline-secondary">
                <i class="fa fa-sync"></i> Làm mới
            </a>
            <a href="/admin/addblogcategory" class="btn btn-success">
                <i class="fa fa-plus"></i> Thêm danh mục
            </a>
        </div>
    </div>

    <!-- CATEGORIES TABLE -->
    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Danh mục</th>
                    <th>Slug</th>
                    <th>Mô tả</th>
                    <th>Danh mục cha</th>
                    <th>Trạng thái</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                    if (!empty($categories)): 
                        // Map categories for parent name lookup
                        $categoryLookup = [];
                        foreach ($categories as $cat) {
                            $categoryLookup[$cat['id']] = $cat['name'];
                        }
                        
                        foreach ($categories as $item): 
                ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($item['slug']); ?></code></td>
                            <td><?php echo htmlspecialchars($item['description'] ?? ''); ?></td>
                            <td>
                                <?php 
                                    if ($item['parent_id'] && isset($categoryLookup[$item['parent_id']])) {
                                        echo '<span class="badge bg-light text-dark">' . htmlspecialchars($categoryLookup[$item['parent_id']]) . '</span>';
                                    } else {
                                        echo '<span class="text-muted">—</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-group d-flex">
                                    <a href="/admin/editblogcategory?id=<?php echo $item['id']; ?>" class="action-btn btn-edit mr-2 text-warning" title="Chỉnh sửa">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <form action="/admin/deleteblogcategory" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="action-btn btn-delete text-danger border-0 bg-transparent" title="Xóa" style="cursor: pointer; padding: 0;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
