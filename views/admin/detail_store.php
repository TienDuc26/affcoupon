<style>
    .admin-detail-store {
        background: #f4f4f4;
        color: #222;
        padding: 15px;
        border-radius: 6px;
    }

    .admin-detail-store a {
        text-decoration: none;
        color: inherit;
    }

    /* LAYOUT */
    .page-wrap {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* SIDEBAR */
    .sidebar-card,
    .coupon-card,
    .content-box {
        background: white;
        border: 1px solid #e2e2e2;
        border-radius: 4px;
    }

    .sidebar-card {
        padding: 24px;
        text-align: center;
        margin-bottom: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .brand-logo {
        width: 100px;
        height: 100px;
        margin: 0 auto 15px;
        background: #171717;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
    }

    .store-name {
        color: #11a611;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .rating {
        color: #ffb300;
        font-size: 20px;
        margin-bottom: 6px;
    }

    .votes {
        color: #777;
        margin-bottom: 12px;
        font-size: 0.85rem;
    }

    .rate-btn {
        display: inline-block;
        padding: 6px 15px;
        background: #ffe9c7;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 15px;
        color: #333;
        font-weight: 500;
    }

    .alert-btn {
        display: block;
        width: 100%;
        border: 2px solid #2ba82b;
        color: #0e8f0e;
        padding: 10px;
        border-radius: 4px;
        font-weight: 600;
        transition: 0.2s;
        text-align: center;
        font-size: 0.9rem;
    }

    .alert-btn:hover {
        background: #1aa11a;
        color: white;
        text-decoration: none;
    }

    .stats-card {
        padding: 20px;
    }

    .stats-title {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .stats-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        color: #555;
        font-size: 0.85rem;
    }

    .stats-row:last-child {
        border-bottom: none;
    }

    /* MAIN */
    .page-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #111;
    }

    .page-subtitle {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .tabs {
        display: flex;
        gap: 14px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .tab {
        padding: 8px 20px;
        border-radius: 4px;
        background: #f3f3f3;
        font-weight: 600;
        color: #1ca61c;
    }

    .tab.active {
        background: #16a516;
        color: white;
    }

    /* COUPON CARD */
    .coupon-card {
        display: flex;
        align-items: stretch;
        margin-bottom: 15px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .discount-side {
        width: 140px;
        border-right: 1px dashed #d5d5d5;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        text-align: center;
        background-color: #fafafa;
    }

    .discount-value {
        color: #0ba80b;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.2;
        word-break: break-all;
    }

    .coupon-content {
        flex: 1;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .verified {
        color: #0ca80c;
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .coupon-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: #111;
    }

    .coupon-desc {
        color: #666;
        font-size: 0.9rem;
    }

    .deal-btn {
        min-width: 110px;
        height: 44px;
        border: none;
        background: #08aa08;
        color: white;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .deal-btn:hover {
        background: #068f06;
    }

    /* CONTENT BOXES */
    .section-title {
        font-size: 1.5rem;
        margin: 25px 0 12px;
        font-weight: 600;
        color: #111;
    }

    .content-box {
        padding: 20px;
        margin-bottom: 18px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .content-box h3 {
        font-size: 1.2rem;
        margin-bottom: 12px;
        color: #222;
    }

    .content-box p {
        color: #444;
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .faq-item {
        padding: 12px 0;
        border-bottom: 1px solid #ededed;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: #222;
    }

    .faq-answer {
        color: #555;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .page-wrap {
            grid-template-columns: 1fr;
        }

        .coupon-card {
            flex-direction: column;
        }

        .discount-side {
            width: 100%;
            border-right: none;
            border-bottom: 1px dashed #d5d5d5;
        }

        .coupon-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .deal-btn {
            width: 100%;
        }
    }

    /* CUSTOM MODAL OVERLAY */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        backdrop-filter: blur(4px);
        transition: opacity 0.3s ease;
    }

    /* CUSTOM MODAL CONTENT */
    .custom-modal-content {
        background-color: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* GREEN TOP BAR */
    .custom-modal-header-bar {
        height: 6px;
        background-color: #08aa08;
        width: 100%;
    }

    /* CLOSE BUTTON */
    .custom-modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        color: #888;
        cursor: pointer;
        transition: color 0.2s;
        line-height: 1;
    }

    .custom-modal-close:hover {
        color: #333;
    }

    /* BODY */
    .custom-modal-body {
        padding: 40px 30px;
        text-align: center;
    }

    .modal-store-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .modal-logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border: 1px solid #eee;
        border-radius: 50%;
        padding: 5px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .modal-title {
        font-size: 1.6rem;
        font-weight: 600;
        color: #222;
        margin: 0;
        text-align: left;
        max-width: 380px;
    }

    .modal-instruction {
        font-size: 1.05rem;
        color: #555;
        margin-bottom: 20px;
    }

    /* CODE BOX WITH DASHED BORDER AND COPY BUTTON */
    .modal-code-wrapper {
        display: flex;
        align-items: stretch;
        border: 2px dashed #08aa08;
        border-radius: 6px;
        background-color: #f7fcf7;
        margin: 0 auto 25px;
        max-width: 420px;
        overflow: hidden;
    }

    .modal-code-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 12px 20px;
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
        text-align: center;
        letter-spacing: 1px;
        outline: none;
        width: 100%;
    }

    .modal-copy-btn {
        border: none;
        background-color: #08aa08;
        color: white;
        padding: 0 25px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .modal-copy-btn:hover {
        background-color: #068f06;
    }

    .modal-copy-btn.copied {
        background-color: #4caf50;
    }

    .modal-more-link {
        display: inline-block;
        color: #08aa08;
        font-weight: 600;
        font-size: 1.05rem;
        text-decoration: none;
        margin-top: 10px;
        transition: color 0.2s;
    }

    .modal-more-link:hover {
        color: #068f06;
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .modal-store-info {
            flex-direction: column;
            text-align: center;
        }
        .modal-title {
            text-align: center;
        }
        .modal-code-wrapper {
            flex-direction: column;
            border-style: dashed;
        }
        .modal-copy-btn {
            padding: 12px 0;
        }
    }
</style>

<div class="admin-detail-store">

    <!-- BREADCRUMB / ACTIONS BAR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="/admin/dashboardadmin" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại Dashboard</a>
        </div>
        <div>
            <a href="/admin/editstore?id=<?php echo $coupon['id']; ?>" class="btn btn-warning btn-sm text-white"><i class="fas fa-pen"></i> Chỉnh sửa Store</a>
        </div>
    </div>

    <div class="page-wrap">

        <!-- SIDEBAR -->
        <aside>
            <div class="sidebar-card">
                <?php if (!empty($coupon['thumbnail_url'])): ?>
                    <img src="<?php echo htmlspecialchars($coupon['thumbnail_url']); ?>" class="img-fluid rounded mb-3" style="max-height: 120px; object-fit: contain; width: 80%;" />
                <?php else: ?>
                    <div class="brand-logo"><?php echo htmlspecialchars(substr($coupon['title'], 0, 2)); ?></div>
                <?php endif; ?>
                
                <div class="store-name"><?php echo htmlspecialchars($coupon['title']); ?></div>
                <div class="rating">★★★★★</div>
                <div class="votes">5.0 / 1,799 votes</div>
                
                <a href="#" class="rate-btn">Rate it</a>
                <a href="<?php echo htmlspecialchars($coupon['affiliate_url']); ?>" target="_blank" class="alert-btn">Get Coupon Alert</a>
            </div>

            <div class="sidebar-card stats-card">
                <div class="stats-title"><?php echo count($vouchers); ?> Verified Coupons</div>

                <div class="stats-row">
                    <span>Deals</span>
                    <span><?php echo count($vouchers); ?></span>
                </div>

                <div class="stats-row">
                    <span>Best Offer</span>
                    <strong><?php echo !empty($bestOffer) ? htmlspecialchars($bestOffer['discount_text']) : 'N/A'; ?></strong>
                </div>
            </div>
        </aside>

        <!-- CONTENT -->
        <main>
            <h1 class="page-title"><a href="#"><?php echo htmlspecialchars($coupon['title']); ?></a> Coupons and Promo Codes</h1>

            <p class="page-subtitle">
                Discover difference discounts now! This coupon are yours only if you want it.
            </p>

            <!-- TABS -->
            <div class="tabs">
                <div class="tab active">Verified</div>
            </div>

            <!-- COUPON CARD LIST -->
            <?php if (!empty($vouchers)): ?>
                <?php foreach ($vouchers as $item): ?>
                    <div class="coupon-card">
                        <div class="discount-side">
                            <div class="discount-value"><?php echo htmlspecialchars($item['voucher_code']); ?></div>
                        </div>

                        <div class="coupon-content">
                            <div>
                                <div class="verified"><i class="fas fa-check-circle"></i> Verified Deal</div>
                                <div class="coupon-title">
                                    <?php echo htmlspecialchars($item['discount_text']); ?>
                                </div>
                                <div class="coupon-desc">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </div>
                            </div>
                            <button class="deal-btn" onclick="handleGetDeal(this, <?php echo htmlspecialchars(json_encode($item['voucher_code'])); ?>, <?php echo htmlspecialchars(json_encode($item['discount_text'])); ?>, <?php echo htmlspecialchars(json_encode($coupon['affiliate_url'])); ?>)">Get Deal</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card p-4 text-center my-4">
                    <p class="text-muted mb-0">Hiện chưa có voucher nào cho cửa hàng này.</p>
                </div>
            <?php endif; ?>

            <!-- ABOUT -->
            <h2 class="section-title">About store</h2>
            <div class="content-box">
                <h3>ABOUT <?php echo htmlspecialchars($coupon['title']); ?></h3>
                <div class="store-description-content">
                    <?php echo $coupon['content'] ?? 'No description available.'; ?>
                </div>
            </div>
            <!-- Hướng dẫn áp dụng mã giảm giá -->
            <h4 class="section-title">How to apply <strong><?php echo htmlspecialchars($coupon['title']); ?></strong> coupon codes?</h4>
<div class="content-box">
    <p><strong>Step 1:</strong> Find your <strong><?php echo htmlspecialchars($coupon['title']); ?></strong> Coupons, discount codes on this page or Coupons Peak and click "GET CODE" button to view the code, then click "Copy" and the coupons, discount codes will be copied to your phone's or computer's clipboard.</p>
    <p><strong>Step 2:</strong> Go to <strong><?php echo htmlspecialchars($coupon['title']); ?></strong> then select all items you want to buy and add to shopping cart. When finished shopping, go to the <strong><?php echo htmlspecialchars($coupon['title']); ?></strong> checkout page.</p>
    <p><strong>Step 3:</strong> During checkout, find the text "Promo Code" or "Discount Code" and paste your <strong><?php echo htmlspecialchars($coupon['title']); ?></strong> coupons, discount codes in step 1 to this box. Click "Apply" and your savings for <strong><?php echo htmlspecialchars($coupon['title']); ?></strong> will be applied.</p>
</div>
            <!-- FAQS -->
            <?php if (!empty($faqs)): ?>
                <h2 class="section-title">FAQs</h2>
                <div class="content-box">
                    <?php foreach ($faqs as $item): ?>
                        <div class="faq-item">
                            <div class="faq-question"><i class="far fa-question-circle text-success"></i> <?php echo htmlspecialchars($item['question']); ?></div>
                            <div class="faq-answer"><?php echo nl2br(htmlspecialchars($item['answer'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>

    </div>
</div>

<!-- Modal structure -->
<div id="couponModal" class="custom-modal-overlay" style="display: none;">
    <div class="custom-modal-content">
        <div class="custom-modal-header-bar"></div>
        <span class="custom-modal-close" onclick="closeCouponModal()">&times;</span>
        
        <div class="custom-modal-body">
            <div class="modal-store-info">
                <?php if (!empty($coupon['thumbnail_url'])): ?>
                    <img src="<?php echo htmlspecialchars($coupon['thumbnail_url']); ?>" id="modalStoreLogo" class="modal-logo" />
                <?php else: ?>
                    <div class="modal-logo brand-logo" style="margin: 0;"><?php echo htmlspecialchars(substr($coupon['title'], 0, 2)); ?></div>
                <?php endif; ?>
                <h3 id="modalDiscountText" class="modal-title">Get 20% Off Select Items</h3>
            </div>

            <p class="modal-instruction">Copy the code and go to <span id="modalStoreName" class="text-success" style="font-weight: 600;"><?php echo htmlspecialchars($coupon['title']); ?></span></p>
            
            <div class="modal-code-wrapper">
                <input type="text" id="modalVoucherCode" class="modal-code-input" readonly value="" />
                <button id="modalCopyBtn" class="modal-copy-btn" onclick="copyVoucherCode()">Tap To Copy</button>
            </div>
            
            <a href="#" id="modalAffiliateLink" target="_blank" class="modal-more-link">More <?php echo htmlspecialchars($coupon['title']); ?> >></a>
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
    // Set loading state
    var originalText = btnElement.textContent;
    btnElement.textContent = 'Redirecting...';
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
    affLink.textContent = 'More ' + <?php echo json_encode($coupon['title']); ?> + ' >>';
    
    // Reset copy button state
    var copyBtn = document.getElementById('modalCopyBtn');
    copyBtn.textContent = 'Tap To Copy';
    copyBtn.classList.remove('copied');

    document.getElementById('couponModal').style.display = 'flex';
}

function closeCouponModal() {
    document.getElementById('couponModal').style.display = 'none';
}

function copyVoucherCode() {
    var codeInput = document.getElementById('modalVoucherCode');
    
    // Select the text field
    codeInput.select();
    codeInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(codeInput.value).then(function() {
        var copyBtn = document.getElementById('modalCopyBtn');
        copyBtn.textContent = 'Copied!';
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
