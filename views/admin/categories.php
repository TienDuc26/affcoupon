<link rel="stylesheet" href="/css/categorymanagement.css" />

<div class="content-card">

    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">Quản lý danh mục</h2>
            <p class="page-subtitle text-muted mb-0">Danh sách danh mục hiện có</p>
        </div>
        <a href="/admin/addcategory" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm danh mục
        </a>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table custom-table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td class="fw-bold"><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($item['slug']); ?></code></td>
                            <td><?php echo htmlspecialchars($item['description'] ?? ''); ?></td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($item['created_at'])); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- VIEW -->
                                    <button type="button" class="btn btn-view btn-sm btn-action mr-1" data-bs-toggle="modal" data-bs-target="#categoryModal-<?php echo $item['id']; ?>" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- EDIT -->
                                    <button type="button" class="btn btn-edit btn-sm btn-action mr-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal-<?php echo $item['id']; ?>" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <!-- DELETE -->
                                    <button type="button" class="btn btn-delete btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal-<?php echo $item['id']; ?>" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Chưa có danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- MODALS -->
<?php if (!empty($categories)): ?>
    <?php foreach ($categories as $item): ?>
        
        <!-- DELETE MODAL -->
        <div class="modal fade" id="deleteModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content delete-modal">
                    <div class="modal-header border-0 pb-0 justify-content-between">
                        <h5 class="modal-title text-danger">Xóa danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="delete-icon mb-3 text-danger" style="font-size: 3rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4 class="mb-3">Bạn có chắc chắn muốn xóa?</h4>
                        <p class="text-muted">
                            Danh mục: <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                            <small class="text-danger">Cảnh báo: Thao tác này không thể hoàn tác nếu danh mục trống!</small>
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                        <form action="/admin/deletecategory" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div class="modal fade" id="editCategoryModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white justify-content-between">
                        <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/editcategory?id=<?php echo $item['id']; ?>" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="Id" value="<?php echo $item['id']; ?>" />

                            <!-- NAME -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Tên danh mục <span class="text-danger">*</span></label>
                                <input type="text" name="Name" value="<?php echo htmlspecialchars($item['name']); ?>" class="form-control" required />
                            </div>

                            <!-- SLUG -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Slug (Tự động sinh khi lưu)</label>
                                <input type="text" value="<?php echo htmlspecialchars($item['slug']); ?>" class="form-control bg-light" readonly />
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Mô tả</label>
                                <textarea name="Description" rows="3" class="form-control"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                            </div>

                            <!-- STATUS -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="IsActive" value="1" id="activeSw-<?php echo $item['id']; ?>" <?php echo $item['is_active'] ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="activeSw-<?php echo $item['id']; ?>">
                                    Hiển thị danh mục này
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DETAIL MODAL -->
        <div class="modal fade" id="categoryModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white justify-content-between">
                        <h5 class="modal-title">Chi tiết danh mục</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-4 text-muted">ID:</div>
                            <div class="col-8 font-weight-bold"><?php echo $item['id']; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Tên:</div>
                            <div class="col-8"><strong><?php echo htmlspecialchars($item['name']); ?></strong></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Slug:</div>
                            <div class="col-8"><code><?php echo htmlspecialchars($item['slug']); ?></code></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Mô tả:</div>
                            <div class="col-8"><?php echo nl2br(htmlspecialchars($item['description'] ?? 'Không có mô tả.')); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Trạng thái:</div>
                            <div class="col-8">
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Hidden</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 text-muted">Ngày tạo:</div>
                            <div class="col-8"><?php echo date('d/m/Y H:i:s', strtotime($item['created_at'])); ?></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>
