<link rel="stylesheet" href="/css/usermanagement.css" />

<div class="content-card">

    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">Quản lý User</h2>
            <p class="page-subtitle text-muted mb-0">Danh sách tài khoản người dùng</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus"></i> Thêm User
        </button>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table custom-table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td class="fw-bold"><strong><?php echo htmlspecialchars($item['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($item['email']); ?></td>
                            <td><?php echo htmlspecialchars($item['full_name'] ?? ''); ?></td>
                            <td>
                                <?php if ($item['role_id'] == 1): ?>
                                    <span class="badge bg-primary text-white"><i class="fas fa-user-shield"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-white"><i class="fas fa-user"></i> User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($item['created_at'])); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- VIEW -->
                                    <button class="btn btn-view btn-sm btn-action mr-1" data-bs-toggle="modal" data-bs-target="#viewModal-<?php echo $item['id']; ?>" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- EDIT -->
                                    <button class="btn btn-edit btn-sm btn-action mr-1" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $item['id']; ?>" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <!-- DELETE -->
                                    <button class="btn btn-delete btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal-<?php echo $item['id']; ?>" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy tài khoản nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white justify-content-between">
                <h5 class="modal-title">Thêm User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/adduser" method="POST">
                <div class="modal-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Username <span class="text-danger">*</span></label>
                        <input name="Username" class="form-control" required />
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Email <span class="text-danger">*</span></label>
                        <input name="Email" type="email" class="form-control" required />
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Full Name</label>
                        <input name="FullName" class="form-control" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Password <span class="text-danger">*</span></label>
                        <input name="Password" type="password" class="form-control" required />
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Quyền hạn <span class="text-danger">*</span></label>
                        <select name="RoleId" class="form-control form-select" required>
                            <option value="2" selected>User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>

                    <!-- Active -->
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" name="IsActive" value="1" class="form-check-input" id="addUserActive" checked />
                        <label class="form-check-label" for="addUserActive">Kích hoạt tài khoản</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> Thêm User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ITEM MODALS -->
<?php if (!empty($users)): ?>
    <?php foreach ($users as $item): ?>
        
        <!-- DETAIL MODAL -->
        <div class="modal fade" id="viewModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white justify-content-between">
                        <h5 class="modal-title">Chi tiết User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-4 text-muted">ID:</div>
                            <div class="col-8 font-weight-bold"><?php echo $item['id']; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Username:</div>
                            <div class="col-8"><strong><?php echo htmlspecialchars($item['username']); ?></strong></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Email:</div>
                            <div class="col-8"><?php echo htmlspecialchars($item['email']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Họ tên:</div>
                            <div class="col-8"><?php echo htmlspecialchars($item['full_name'] ?? 'Chưa cập nhật'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Quyền hạn:</div>
                            <div class="col-8">
                                <?php if ($item['role_id'] == 1): ?>
                                    <span class="badge bg-primary text-white">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-white">User</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Trạng thái:</div>
                            <div class="col-8">
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Bị khóa</span>
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

        <!-- EDIT MODAL -->
        <div class="modal fade" id="editModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white justify-content-between">
                        <h5 class="modal-title">Chỉnh sửa User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/edituser" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="Id" value="<?php echo $item['id']; ?>" />

                            <!-- Username -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Username <span class="text-danger">*</span></label>
                                <input name="Username" value="<?php echo htmlspecialchars($item['username']); ?>" class="form-control" required />
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input name="Email" type="email" value="<?php echo htmlspecialchars($item['email']); ?>" class="form-control" required />
                            </div>

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Full Name</label>
                                <input name="FullName" value="<?php echo htmlspecialchars($item['full_name'] ?? ''); ?>" class="form-control" />
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Quyền hạn <span class="text-danger">*</span></label>
                                <select name="RoleId" class="form-control form-select" required>
                                    <option value="2" <?php echo ($item['role_id'] == 2) ? 'selected' : ''; ?>>User</option>
                                    <option value="1" <?php echo ($item['role_id'] == 1) ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" name="IsActive" value="1" class="form-check-input" id="editUserActive-<?php echo $item['id']; ?>" <?php echo $item['is_active'] ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="editUserActive-<?php echo $item['id']; ?>">Kích hoạt tài khoản</label>
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

        <!-- DELETE CONFIRM MODAL -->
        <div class="modal fade" id="deleteModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 justify-content-between">
                        <h5 class="modal-title text-danger">Xóa tài khoản</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="delete-icon text-danger mb-3" style="font-size: 3rem;">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <p class="mb-0">Bạn có chắc chắn muốn xóa tài khoản này?</p>
                        <div class="alert alert-warning mt-3 mb-0">
                            <strong><?php echo htmlspecialchars($item['username']); ?></strong> - <?php echo htmlspecialchars($item['email']); ?>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                        <form action="/admin/deleteuser" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>
