<link rel="stylesheet" href="/css/offermanagement.css" />

<div class="content-card">

    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">Quản lý Offer</h2>
            <p class="page-subtitle text-muted mb-0">Danh sách mã giảm giá và ưu đãi</p>
        </div>
        <a href="/admin/addoffer" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm Offer
        </a>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-box mb-4">
        <form method="GET" action="/admin/offermanagement" class="row g-3 align-items-center">
            <!-- SEARCH -->
            <div class="col-md-4">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" class="form-control custom-input" placeholder="Tìm kiếm voucher..." />
            </div>

            <!-- STATUS -->
            <div class="col-md-3">
                <select name="status" class="form-select form-control custom-input">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" <?php echo ($status === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="hidden" <?php echo ($status === 'hidden') ? 'selected' : ''; ?>>Hidden</option>
                </select>
            </div>

            <!-- TYPE -->
            <div class="col-md-3">
                <select name="type" class="form-select form-control custom-input">
                    <option value="">Tất cả loại</option>
                    <option value="featured" <?php echo ($type === 'featured') ? 'selected' : ''; ?>>Featured</option>
                    <option value="normal" <?php echo ($type === 'normal') ? 'selected' : ''; ?>>Normal</option>
                </select>
            </div>

            <!-- BUTTON -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table custom-table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Coupon/Store</th>
                    <th>Voucher Code</th>
                    <th>Discount</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Expired Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($offers)): ?>
                    <?php foreach ($offers as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td class="fw-bold"><strong><?php echo htmlspecialchars($item['coupon_title']); ?></strong></td>
                            <td><span class="voucher-code font-monospace bg-light border px-2 py-1 rounded"><?php echo htmlspecialchars($item['voucher_code']); ?></span></td>
                            <td><?php echo htmlspecialchars($item['discount_text']); ?></td>
                            <td>
                                <?php if ($item['is_featured']): ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> Featured</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-white">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                    <span class="badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $item['expired_date'] ? date('d/m/Y', strtotime($item['expired_date'])) : '<span class="text-muted">N/A</span>'; ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- VIEW -->
                                    <button type="button" class="btn btn-view btn-sm btn-action mr-1" data-bs-toggle="modal" data-bs-target="#detailModal-<?php echo $item['id']; ?>" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- EDIT -->
                                    <button type="button" class="btn btn-edit btn-sm btn-action mr-1" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $item['id']; ?>" title="Sửa">
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
                        <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy offer nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- MODALS -->
<?php if (!empty($offers)): ?>
    <?php foreach ($offers as $item): ?>
        
        <!-- DETAIL MODAL -->
        <div class="modal fade" id="detailModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white justify-content-between">
                        <h5 class="modal-title">Chi tiết Offer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-4 text-muted">ID:</div>
                            <div class="col-8 font-weight-bold"><?php echo $item['id']; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Coupon/Store:</div>
                            <div class="col-8"><strong><?php echo htmlspecialchars($item['coupon_title']); ?></strong></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Voucher Code:</div>
                            <div class="col-8"><code style="font-size: 1.1rem;"><?php echo htmlspecialchars($item['voucher_code']); ?></code></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Discount Text:</div>
                            <div class="col-8"><?php echo htmlspecialchars($item['discount_text']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Mô tả:</div>
                            <div class="col-8"><?php echo nl2br(htmlspecialchars($item['description'] ?? 'Không có mô tả.')); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Bắt đầu:</div>
                            <div class="col-8"><?php echo $item['start_date'] ? date('d/m/Y H:i', strtotime($item['start_date'])) : 'N/A'; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 text-muted">Hết hạn:</div>
                            <div class="col-8"><?php echo $item['expired_date'] ? date('d/m/Y H:i', strtotime($item['expired_date'])) : 'N/A'; ?></div>
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

        <!-- EDIT MODAL -->
        <div class="modal fade" id="editModal-<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white justify-content-between">
                        <h5 class="modal-title">Chỉnh sửa Offer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/editoffer" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="Id" value="<?php echo $item['id']; ?>" />

                            <!-- STORE SELECTION -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Chọn Store <span class="text-danger">*</span></label>
                                <select name="CouponId" class="form-control form-select" required>
                                    <option value="">-- Chọn Store --</option>
                                    <?php if (!empty($coupons)): ?>
                                        <?php foreach ($coupons as $c): ?>
                                            <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $item['coupon_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($c['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- VOUCHER CODE -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Voucher Code <span class="text-danger">*</span></label>
                                <input type="text" name="VoucherCode" value="<?php echo htmlspecialchars($item['voucher_code']); ?>" class="form-control edit-offer-code" data-offer-id="<?php echo $item['id']; ?>" required />
                            </div>

                            <!-- DISCOUNT TEXT -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Discount Text <span class="text-danger">*</span></label>
                                <input type="text" name="DiscountText" value="<?php echo htmlspecialchars($item['discount_text']); ?>" class="form-control edit-offer-disc" data-offer-id="<?php echo $item['id']; ?>" required />
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Description</label>
                                <textarea name="Description" class="form-control edit-offer-desc" rows="4" data-offer-id="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                                <small class="text-muted mt-1 d-block"><i class="fas fa-magic"></i> Description will be auto-generated if left blank.</small>
                            </div>

                            <div class="row">
                                <!-- START DATE -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Start Date</label>
                                    <input type="datetime-local" name="StartDate" value="<?php echo $item['start_date'] ? date('Y-m-d\TH:i', strtotime($item['start_date'])) : ''; ?>" class="form-control" />
                                </div>

                                <!-- EXPIRED DATE -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">Expired Date</label>
                                    <input type="datetime-local" name="ExpiredDate" value="<?php echo $item['expired_date'] ? date('Y-m-d\TH:i', strtotime($item['expired_date'])) : ''; ?>" class="form-control" />
                                </div>
                            </div>

                            <div class="row mt-2">
                                <!-- FEATURED -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="IsFeatured" value="1" class="form-check-input" id="featSw-<?php echo $item['id']; ?>" <?php echo $item['is_featured'] ? 'checked' : ''; ?> />
                                        <label class="form-check-label" for="featSw-<?php echo $item['id']; ?>">Featured (Hiển thị hot deal)</label>
                                    </div>
                                </div>

                                <!-- ACTIVE -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="IsActive" value="1" class="form-check-input" id="actSw-<?php echo $item['id']; ?>" <?php echo $item['is_active'] ? 'checked' : ''; ?> />
                                        <label class="form-check-label" for="actSw-<?php echo $item['id']; ?>">Active (Hiển thị offer)</label>
                                    </div>
                                </div>
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
                        <h5 class="modal-title text-danger">Xác nhận xóa Offer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="delete-icon text-danger mb-3" style="font-size: 3rem;">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <p class="mb-0">Bạn có chắc chắn muốn xóa Offer này không?</p>
                        <div class="alert alert-warning mt-3 mb-0">
                            <strong><?php echo htmlspecialchars($item['voucher_code']); ?></strong> - <?php echo htmlspecialchars($item['discount_text']); ?>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                        <form action="/admin/deleteoffer" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>

<script>
(function () {
    function generateDescription(voucherCode, discountText) {
        var code = (voucherCode || '').trim();
        var disc = (discountText || '').trim();
        if (!code && !disc) return '';

        var codeUp = code.toUpperCase();
        var discLow = disc.toLowerCase();

        // 1. Free Shipping
        if (/FREESHIP|SHIP0|MIENSHIP|SHIPPING|FREE_SHIP|FREESHIPPING/.test(codeUp) ||
            /freeship|free\s*ship|mi[eê]n\s*ph[ií]|free\s*shipping/.test(discLow)) {
            var suffix = disc ? ' (' + disc + ')' : '';
            return 'Apply code ' + code + ' at checkout to get free shipping' + suffix + ' on your order.';
        }

        // 2. Percentage Discount
        var pctMatch = disc.match(/(\d+)\s*%/);
        if (pctMatch) {
            return 'Get ' + pctMatch[0] + ' off your purchase when you apply promo code ' + code + ' at checkout.';
        }
        if (/SALE|GIAM|OFF/.test(codeUp)) {
            var numMatch = code.match(/\d+/);
            if (numMatch) {
                return 'Get ' + numMatch[0] + '% off your purchase when you apply promo code ' + code + ' at checkout.';
            }
        }

        // 3. Cash Amount Discount (100k, 50k, 50.000đ, $10)
        if (/\d+\s*(?:k|đ|d|vnd|\$)/i.test(disc)) {
            return 'Enjoy a discount of ' + disc + ' on your order. Use code ' + code + ' during checkout to redeem.';
        }

        // 4. Welcome / New User
        if (/WELCOME|NEWUSER|NEW_USER|MEMBER|DANGKY/.test(codeUp) || /new|welcome/.test(discLow)) {
            var suffix2 = disc ? ' (' + disc + ')' : '';
            return 'Special welcome offer for new customers. Use code ' + code + ' to save' + suffix2 + ' on your first order.';
        }

        // 5. Buy 1 Get 1 / Combo
        if (/BUY1|GET1|BOGO|COMBO|MUA1|TANG1/.test(codeUp) || /combo|buy\s*1|get\s*1/.test(discLow)) {
            var suffix3 = disc ? ' (' + disc + ')' : '';
            return 'Exclusive Buy 1 Get 1 / Combo deal' + suffix3 + '. Enter code ' + code + ' in your cart to claim.';
        }

        // Fallback
        if (disc) {
            return 'Use code ' + code + ' at checkout to receive ' + disc + ' off your order.';
        }
        return 'Apply promo code ' + code + ' at checkout to unlock exclusive deals and savings.';
    }

    // Wire up each edit modal
    document.querySelectorAll('.edit-offer-code, .edit-offer-disc').forEach(function (input) {
        var offerId = input.dataset.offerId;

        input.addEventListener('input', function () {
            var codeEl = document.querySelector('.edit-offer-code[data-offer-id="' + offerId + '"]');
            var discEl = document.querySelector('.edit-offer-disc[data-offer-id="' + offerId + '"]');
            var descEl = document.querySelector('.edit-offer-desc[data-offer-id="' + offerId + '"]');

            if (!descEl || descEl.dataset.userEdited === '1') return;

            var generated = generateDescription(codeEl ? codeEl.value : '', discEl ? discEl.value : '');
            descEl.value = generated;
        });
    });

    // Mark description as user-edited when they type in it
    document.querySelectorAll('.edit-offer-desc').forEach(function (descEl) {
        descEl.addEventListener('input', function () {
            this.dataset.userEdited = this.value.trim() !== '' ? '1' : '0';
        });
    });
})();
</script>
