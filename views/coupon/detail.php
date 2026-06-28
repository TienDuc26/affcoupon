<div class="py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-success text-decoration-none fw-semibold">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/" class="text-success text-decoration-none fw-semibold">Cửa hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($coupon['title']); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- SIDEBAR -->
        <div class="col-lg-4 col-md-12">
            <!-- Brand Profile Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white mb-4">
                <div class="d-flex justify-content-center mb-3">
                    <?php if (!empty($coupon['thumbnail_url'])): ?>
                        <div class="p-3 border rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                            <img src="<?php echo htmlspecialchars($coupon['thumbnail_url']); ?>" class="img-fluid" style="max-height: 80px; object-fit: contain;" />
                        </div>
                    <?php else: ?>
                        <div class="brand-logo rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 2.2rem; font-weight: 800;">
                            <?php echo htmlspecialchars(substr($coupon['title'], 0, 2)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h3 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($coupon['title']); ?></h3>
                
                <div class="rating mb-2">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="text-muted small mb-4">5.0 / 1,799 đánh giá</div>
                
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-success btn-pill">Đánh giá cửa hàng</a>
                    <a href="<?php echo htmlspecialchars($coupon['affiliate_url']); ?>" target="_blank" class="btn btn-success btn-pill">
                        Ghé thăm cửa hàng <i class="fas fa-external-link-alt ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Thông tin ưu đãi</h5>
                
                <div class="d-flex justify-content-between py-2 border-bottom text-secondary">
                    <span>Tổng số voucher</span>
                    <span class="badge bg-success"><?php echo count($vouchers); ?></span>
                </div>

                <div class="d-flex justify-content-between py-2 text-secondary">
                    <span>Khuyến mãi lớn nhất</span>
                    <strong class="text-success"><?php echo !empty($bestOffer) ? htmlspecialchars($bestOffer['discount_text']) : 'N/A'; ?></strong>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-8 col-md-12">
            <h1 class="fw-bold text-dark mb-2">Mã giảm giá <?php echo htmlspecialchars($coupon['title']); ?></h1>
            <p class="text-muted lead mb-4">Danh sách mã giảm giá, coupons và chương trình khuyến mãi <?php echo htmlspecialchars($coupon['title']); ?> còn hoạt động.</p>

            <!-- TABS -->
            <ul class="nav nav-tabs border-bottom mb-4">
                <li class="nav-item">
                    <a class="nav-link active text-success fw-bold border-success-subtle" aria-current="page" href="#">Vouchers khả dụng (<?php echo count($vouchers); ?>)</a>
                </li>
            </ul>

            <!-- COUPON LIST -->
            <?php if (!empty($vouchers)): ?>
                <?php foreach ($vouchers as $item): ?>
                    <div class="coupon-ticket">
                        <div class="ticket-left">
                            <span class="voucher-code-label">
                                <?php echo htmlspecialchars($item['voucher_code'] ?: 'DEAL HOT'); ?>
                            </span>
                        </div>

                        <div class="ticket-right">
                            <div class="pe-md-4">
                                <span class="badge bg-success-subtle text-success small mb-2">
                                    <i class="fas fa-check-circle me-1"></i> Đã xác thực
                                </span>
                                <h5 class="ticket-title"><?php echo htmlspecialchars($item['discount_text']); ?></h5>
                                <p class="ticket-desc"><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                            <div>
                                <button class="btn btn-success ticket-btn" onclick="handleGetDeal(this, <?php echo htmlspecialchars(json_encode($item['voucher_code'])); ?>, <?php echo htmlspecialchars(json_encode($item['discount_text'])); ?>, <?php echo htmlspecialchars(json_encode($coupon['affiliate_url'])); ?>)">
                                    <?php echo !empty($item['voucher_code']) ? 'Lấy Mã' : 'Nhận Deal'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state mb-5">
                    <i class="fa-regular fa-face-frown"></i>
                    <h4>Chưa có voucher nào</h4>
                    <p class="text-muted">Hiện cửa hàng chưa cập nhật mã giảm giá mới. Vui lòng quay lại sau.</p>
                </div>
            <?php endif; ?>

            <!-- ABOUT STORE -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h4 class="fw-bold text-success mb-3 border-bottom pb-2">Về <?php echo htmlspecialchars($coupon['title']); ?></h4>
                <div class="text-secondary" style="line-height: 1.8;">
                    <?php echo $coupon['content'] ?? 'Chưa có thông tin giới thiệu chi tiết về cửa hàng này.'; ?>
                </div>
            </div>

            <!-- APPLICATION GUIDE -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h4 class="fw-bold text-success mb-3 border-bottom pb-2">Cách áp dụng mã giảm giá <?php echo htmlspecialchars($coupon['title']); ?>?</h4>
                <div class="row g-4 mt-1">
                    <div class="col-md-4 text-center">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="badge bg-success rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 1rem;">1</div>
                            <h6 class="fw-bold text-dark">Lấy mã giảm giá</h6>
                            <p class="small text-muted mb-0">Tìm ưu đãi, click "Lấy Mã" và hệ thống sẽ tự động sao chép mã code vào khay nhớ tạm.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="badge bg-success rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 1rem;">2</div>
                            <h6 class="fw-bold text-dark">Chọn sản phẩm</h6>
                            <p class="small text-muted mb-0">Hệ thống chuyển bạn đến store, chọn sản phẩm cần mua và thêm vào giỏ hàng.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="badge bg-success rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 1rem;">3</div>
                            <h6 class="fw-bold text-dark">Áp dụng mã & thanh toán</h6>
                            <p class="small text-muted mb-0">Dán mã code tại ô "Mã giảm giá" ở bước thanh toán để nhận giảm khấu trừ tự động.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQS -->
            <?php if (!empty($faqs)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="fw-bold text-success mb-3 border-bottom pb-2">Câu hỏi thường gặp (FAQs)</h4>
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <?php foreach ($faqs as $index => $item): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading-<?php echo $index; ?>">
                                    <button class="accordion-button collapsed fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse-<?php echo $index; ?>" aria-expanded="false" aria-controls="faqCollapse-<?php echo $index; ?>">
                                        <i class="far fa-question-circle text-success me-2"></i> <?php echo htmlspecialchars($item['question']); ?>
                                    </button>
                                </h2>
                                <div id="faqCollapse-<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="faqHeading-<?php echo $index; ?>" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary" style="line-height: 1.7;">
                                        <?php echo nl2br(htmlspecialchars($item['answer'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal structure -->
<div id="couponModal" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div class="custom-modal-header-bar"></div>
        <span class="custom-modal-close" onclick="closeCouponModal()">&times;</span>
        
        <div class="custom-modal-body">
            <div class="modal-store-info">
                <?php if (!empty($coupon['thumbnail_url'])): ?>
                    <img src="<?php echo htmlspecialchars($coupon['thumbnail_url']); ?>" id="modalStoreLogo" class="modal-logo" />
                <?php else: ?>
                    <div class="modal-logo brand-logo bg-success text-white d-flex align-items-center justify-content-center" style="margin: 0; font-size: 1.5rem; font-weight: 800; width: 64px; height: 64px;">
                        <?php echo htmlspecialchars(substr($coupon['title'], 0, 2)); ?>
                    </div>
                <?php endif; ?>
                <h3 id="modalDiscountText" class="modal-title">Get 20% Off Select Items</h3>
            </div>

            <p class="modal-instruction mb-3 text-secondary">Sao chép mã giảm giá dưới đây và sử dụng tại cửa hàng <span id="modalStoreName" class="text-success fw-bold"><?php echo htmlspecialchars($coupon['title']); ?></span></p>
            
            <div class="modal-code-wrapper">
                <input type="text" id="modalVoucherCode" class="modal-code-input" readonly value="" />
                <button id="modalCopyBtn" class="modal-copy-btn" onclick="copyVoucherCode()">Sao chép</button>
            </div>
            
            <a href="#" id="modalAffiliateLink" target="_blank" class="modal-more-link text-success text-decoration-none fw-bold mt-3 d-inline-block">Mua ngay tại <?php echo htmlspecialchars($coupon['title']); ?> <i class="fas fa-chevron-right small"></i></a>
        </div>
    </div>
</div>

<script>
/**
 * Main entry point for "Get Deal" button.
 * - If coupon has a code: open modal to show/copy the code.
 * - If coupon has NO code: redirect directly to the affiliate/deal URL.
 */
function handleGetDeal(btnElement, code, discountText, affiliateUrl) {
    var hasCode = code && code.trim() !== '';

    if (hasCode) {
        openCouponModal(code, discountText, affiliateUrl);
    } else {
        redirectToDeal(btnElement, affiliateUrl);
    }
}

/**
 * Redirect directly to the affiliate/deal URL (no-code path).
 * Shows a brief loading state on the button before redirecting.
 */
function redirectToDeal(btnElement, affiliateUrl) {
    var originalText = btnElement.textContent;
    btnElement.textContent = 'Đang chuyển hướng...';
    btnElement.disabled = true;

    // Open deal in new tab and restore button state
    window.open(affiliateUrl, '_blank');

    setTimeout(function() {
        btnElement.textContent = originalText;
        btnElement.disabled = false;
    }, 1000);
}

/**
 * Open the coupon code modal (has-code path).
 * Populates the modal with code, discount text, and affiliate link.
 */
function openCouponModal(code, discountText, affiliateUrl) {
    document.getElementById('modalVoucherCode').value = code;
    document.getElementById('modalDiscountText').textContent = discountText;
    
    var affLink = document.getElementById('modalAffiliateLink');
    affLink.href = affiliateUrl;
    affLink.innerHTML = 'Mua ngay tại ' + <?php echo json_encode($coupon['title']); ?> + ' <i class="fas fa-chevron-right small"></i>';
    
    // Reset copy button state
    var copyBtn = document.getElementById('modalCopyBtn');
    copyBtn.textContent = 'Sao chép';
    copyBtn.classList.remove('copied');

    document.getElementById('couponModal').classList.add('show');
}

function closeCouponModal() {
    document.getElementById('couponModal').classList.remove('show');
}

function copyVoucherCode() {
    var codeInput = document.getElementById('modalVoucherCode');
    
    // Select the text field
    codeInput.select();
    codeInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(codeInput.value).then(function() {
        var copyBtn = document.getElementById('modalCopyBtn');
        copyBtn.textContent = 'Đã chép!';
        copyBtn.classList.add('copied');
        
        // After copying, open the affiliate link in a new tab to redirect the user
        var affLink = document.getElementById('modalAffiliateLink').href;
        window.open(affLink, '_blank');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}

// Close modal when clicking outside content
window.onclick = function(event) {
    var modal = document.getElementById('couponModal');
    if (event.target == modal) {
        closeCouponModal();
    }
}
</script>

