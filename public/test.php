<?php
// Enable maximum error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic Test Page</h1>";

try {
    echo "1. Checking directory paths...<br>";
    $root = dirname(__DIR__);
    echo "Root Path: " . $root . "<br>";

    echo "2. Requiring base controller...<br>";
    require_once $root . '/controllers/BaseController.php';
    echo "BaseController Loaded.<br>";

    echo "3. Requiring CouponController...<br>";
    require_once $root . '/controllers/CouponController.php';
    echo "CouponController Loaded.<br>";

    echo "4. Instantiating models...<br>";
    require_once $root . '/models/Coupon.php';
    echo "Coupon model loaded.<br>";
    $couponModel = new Coupon();
    echo "Coupon model instantiated.<br>";

    require_once $root . '/models/Voucher.php';
    echo "Voucher model loaded.<br>";
    $voucherModel = new Voucher();
    echo "Voucher model instantiated.<br>";

    require_once $root . '/models/CouponFAQ.php';
    echo "CouponFAQ model loaded.<br>";
    $faqModel = new CouponFAQ();
    echo "CouponFAQ model instantiated.<br>";

    echo "5. Testing database queries...<br>";
    $slug = 'duc';
    echo "Searching for slug: '$slug'...<br>";
    $coupon = $couponModel->getBySlugWithDetails($slug);
    if ($coupon) {
        echo "Found Coupon ID: " . $coupon['id'] . " - Title: " . $coupon['title'] . "<br>";
        
        echo "Incrementing view count...<br>";
        $couponModel->incrementViewCount($coupon['id']);
        echo "View count incremented.<br>";

        echo "Fetching vouchers...<br>";
        $vouchers = $voucherModel->getByCouponId($coupon['id']);
        echo "Found " . count($vouchers) . " vouchers.<br>";

        echo "Fetching FAQs...<br>";
        $faqs = $faqModel->getByCouponId($coupon['id']);
        echo "Found " . count($faqs) . " FAQs.<br>";
    } else {
        echo "Coupon with slug '$slug' NOT found in database (this is normal if '$slug' doesn't exist). Testing with 'shopee'...<br>";
        $coupon = $couponModel->getBySlugWithDetails('shopee');
        if ($coupon) {
            echo "Found Shopee Coupon ID: " . $coupon['id'] . "<br>";
        } else {
            echo "Shopee coupon also NOT found in database.<br>";
        }
    }

    echo "<h3>All diagnostic tests passed successfully!</h3>";

} catch (Throwable $e) {
    echo "<h2>❌ ERROR CAUGHT:</h2>";
    echo "<strong>Message:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
