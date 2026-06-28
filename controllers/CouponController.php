<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Coupon.php';
require_once __DIR__ . '/../models/Voucher.php';
require_once __DIR__ . '/../models/CouponFAQ.php';

class CouponController extends BaseController {

    public function coupondetail() {
        $couponModel = new Coupon();
        $voucherModel = new Voucher();
        $faqModel = new CouponFAQ();

        $coupon = null;
        if (isset($_GET['slug']) && !empty($_GET['slug'])) {
            $slug = $_GET['slug'];
            $coupon = $couponModel->getBySlugWithDetails($slug);
        } else {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id > 0) {
                $coupon = $couponModel->getByIdWithDetails($id);
            }
        }

        if (!$coupon) {
            http_response_code(404);
            die("Store / Coupon not found");
        }

        $id = $coupon['id'];

        // Increment View Count
        $couponModel->incrementViewCount($id);

        $vouchers = $voucherModel->getByCouponId($id);
        $faqs = $faqModel->getByCouponId($id);
        
        // Fallback to default FAQs if none are provided for this coupon
        if (empty($faqs)) {
            require_once __DIR__ . '/../models/DefaultFAQ.php';
            $faqs = DefaultFAQ::generate($coupon['title'] ?? '');
        }

        $bestOffer = Voucher::getBestOffer($vouchers);

        $this->renderView('coupon/detail', [
            'coupon' => $coupon,
            'vouchers' => $vouchers,
            'faqs' => $faqs,
            'bestOffer' => $bestOffer
        ]);
    }
}
