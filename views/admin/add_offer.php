<link rel="stylesheet" href="/css/offermanagement.css" />

<div class="content-card">

    <!-- HEADER -->
    <div class="page-header mb-4">
        <div>
            <h2 class="page-title">Thêm Offer</h2>
            <p class="page-subtitle text-muted">Tạo mã giảm giá mới</p>
        </div>
    </div>

    <!-- FORM -->
    <form action="/admin/addoffer" method="POST">
        
        <input type="hidden" name="__RequestVerificationToken" value="<?php echo BaseController::generateAntiForgeryToken(); ?>">

        <div class="row">

            <!-- COUPON -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Coupon/Store <span class="text-danger">*</span></label>
                <select name="CouponId" class="form-control form-select custom-input" required>
                    <option value="">-- Chọn Coupon/Store --</option>
                    <?php if (!empty($coupons)): ?>
                        <?php foreach ($coupons as $item): ?>
                            <option value="<?php echo $item['id']; ?>">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- VOUCHER CODE -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Voucher Code <span class="text-danger">*</span></label>
                <input type="text" id="addVoucherCode" name="VoucherCode" class="form-control custom-input" placeholder="VD: SALE50" required />
            </div>

            <!-- DISCOUNT -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Discount Text <span class="text-danger">*</span></label>
                <input type="text" id="addDiscountText" name="DiscountText" class="form-control custom-input" placeholder="VD: Giảm 50%" required />
            </div>

            <!-- START DATE -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Start Date</label>
                <input type="datetime-local" name="StartDate" class="form-control custom-input" />
            </div>

            <!-- EXPIRED DATE -->
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Expired Date</label>
                <input type="datetime-local" name="ExpiredDate" class="form-control custom-input" />
            </div>

            <!-- DESCRIPTION -->
            <div class="col-12 mb-3">
                <label class="form-label font-weight-bold">Description</label>
                <textarea id="addDescription" name="Description" rows="5" class="form-control custom-input" placeholder="Leave blank to auto-generate from Voucher Code & Discount Text"></textarea>
                <small class="text-muted mt-1 d-block"><i class="fas fa-magic"></i> Description will be auto-generated if left blank.</small>
            </div>

            <!-- FEATURED -->
            <div class="col-md-6 mb-4">
                <div class="form-check form-switch">
                    <input type="checkbox" name="IsFeatured" value="1" class="form-check-input" id="isFeatSwitch" />
                    <label class="form-check-label" for="isFeatSwitch">
                        Featured Offer (Hot Deal)
                    </label>
                </div>
            </div>

            <!-- ACTIVE -->
            <div class="col-md-6 mb-4">
                <div class="form-check form-switch">
                    <input type="checkbox" name="IsActive" value="1" class="form-check-input" id="isActiveSwitch" checked />
                    <label class="form-check-label" for="isActiveSwitch">
                        Active
                    </label>
                </div>
            </div>

        </div>

        <!-- BUTTONS -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success px-4 mr-2">
                <i class="fas fa-save"></i> Lưu Offer
            </button>
            <a href="/admin/offermanagement" class="btn btn-outline-secondary px-4">
                Quay lại
            </a>
        </div>

    </form>

</div>

<script>
(function () {
    var codeEl = document.getElementById('addVoucherCode');
    var discEl = document.getElementById('addDiscountText');
    var descEl = document.getElementById('addDescription');

    // Track if user manually edited the description
    var userEdited = false;

    descEl.addEventListener('input', function () {
        userEdited = this.value.trim() !== '';
    });

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

    function tryAutoFill() {
        if (userEdited) return;
        var generated = generateDescription(codeEl.value, discEl.value);
        descEl.value = generated;
    }

    codeEl.addEventListener('input', tryAutoFill);
    discEl.addEventListener('input', tryAutoFill);
})();
</script>
