<?php
require_once __DIR__ . '/../config/database.php';

class Voucher {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByCouponId($couponId) {
        $stmt = $this->db->prepare("
            SELECT * FROM vouchers 
            WHERE coupon_id = ? AND is_active = 1 
            ORDER BY sort_order ASC
        ");
        $stmt->execute([$couponId]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM vouchers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAllForAdmin($keyword = null, $status = null, $type = null) {
        $sql = "SELECT v.*, c.title as coupon_title 
                FROM vouchers v
                LEFT JOIN coupons c ON v.coupon_id = c.id
                WHERE 1=1";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (v.voucher_code LIKE ? OR c.title LIKE ?)";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }

        if (!empty($status)) {
            if ($status === 'active') {
                $sql .= " AND v.is_active = 1";
            } else if ($status === 'hidden') {
                $sql .= " AND v.is_active = 0";
            }
        }

        if (!empty($type)) {
            if ($type === 'featured') {
                $sql .= " AND v.is_featured = 1";
            } else if ($type === 'normal') {
                $sql .= " AND v.is_featured = 0";
            }
        }

        $sql .= " ORDER BY v.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM vouchers");
        return $stmt->fetchColumn();
    }

    public function add($couponId, $voucherCode, $discountText, $description, $startDate, $expiredDate, $isFeatured, $isActive) {
        $sortOrder = $this->countAll() + 1;
        
        $stmt = $this->db->prepare("
            INSERT INTO vouchers (coupon_id, voucher_code, discount_text, description, start_date, expired_date, is_featured, is_active, sort_order, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        return $stmt->execute([
            $couponId,
            $voucherCode,
            $discountText,
            $description,
            !empty($startDate) ? $startDate : null,
            !empty($expiredDate) ? $expiredDate : null,
            $isFeatured ? 1 : 0,
            $isActive ? 1 : 0,
            $sortOrder
        ]);
    }

    public function update($id, $couponId, $voucherCode, $discountText, $description, $startDate, $expiredDate, $isFeatured, $isActive) {
        $stmt = $this->db->prepare("
            UPDATE vouchers SET 
                coupon_id = ?, 
                voucher_code = ?, 
                discount_text = ?, 
                description = ?, 
                start_date = ?, 
                expired_date = ?, 
                is_featured = ?, 
                is_active = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $couponId,
            $voucherCode,
            $discountText,
            $description,
            !empty($startDate) ? $startDate : null,
            !empty($expiredDate) ? $expiredDate : null,
            $isFeatured ? 1 : 0,
            $isActive ? 1 : 0,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM vouchers WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteByCouponId($couponId) {
        $stmt = $this->db->prepare("DELETE FROM vouchers WHERE coupon_id = ?");
        return $stmt->execute([$couponId]);
    }

    public static function getBestOffer($vouchers) {
        if (empty($vouchers)) {
            return null;
        }

        $bestVoucher = null;
        $maxScore = -1;

        foreach ($vouchers as $voucher) {
            $score = 0;

            // 1. Boost if featured
            if (!empty($voucher['is_featured'])) {
                $score += 1000000;
            }

            // 2. Parse discount text to estimate value
            $text = mb_strtolower($voucher['discount_text'] ?? '');
            
            // Look for percentage discount first, e.g., "50%", "30%"
            if (preg_match('/(\d+)\s*%/', $text, $matches)) {
                $percent = (int)$matches[1];
                $score += $percent * 1000;
            } 
            // Look for "k" discount, e.g., "100k", "50k", "30k"
            elseif (preg_match('/(\d+)\s*k/', $text, $matches)) {
                $value = (int)$matches[1] * 1000;
                $score += $value;
            } 
            // Look for "đ" or "đồng" or raw numbers, e.g. "100.000đ", "100000"
            elseif (preg_match('/([\d\.,]+)\s*(đ|đồng)/', $text, $matches)) {
                $cleanNum = preg_replace('/[\.,]/', '', $matches[1]);
                $score += (int)$cleanNum;
            }
            // Look for general numbers in text if no suffix (e.g., "Giảm 50000")
            elseif (preg_match('/giảm\s*([\d\.,]+)/', $text, $matches)) {
                $cleanNum = preg_replace('/[\.,]/', '', $matches[1]);
                $score += (int)$cleanNum;
            }
            // Look for free shipping or freebies
            if (strpos($text, 'miễn phí') !== false || strpos($text, 'freeship') !== false || strpos($text, 'free') !== false) {
                $score += 30000;
            }

            if ($score > $maxScore) {
                $maxScore = $score;
                $bestVoucher = $voucher;
            }
        }

        return $bestVoucher;
    }

    public static function generateDescription($voucherCode, $discountText) {
        $voucherCode = trim($voucherCode);
        $discountText = trim($discountText);

        if (empty($voucherCode) && empty($discountText)) {
            return '';
        }

        $codeUpper = mb_strtoupper($voucherCode);
        $discountLower = mb_strtolower($discountText);

        // 1. Free Shipping
        $isFreeshipCode = preg_match('/FREESHIP|SHIP0|MIENSHIP|SHIPPING|FREE_SHIP|FREESHIPPING/', $codeUpper);
        $isFreeshipDiscount = preg_match('/freeship|free\s*ship|miễn\s*phí\s*vận\s*chuyển|miễn\s*phí\s*ship|free\s*shipping/', $discountLower);
        if ($isFreeshipCode || $isFreeshipDiscount) {
            $suffix = "";
            if (!empty($discountText)) {
                $suffix = " (" . $discountText . ")";
            }
            return "Apply code " . $voucherCode . " at checkout to get free shipping" . $suffix . " on your order.";
        }

        // 2. Percentage Discount
        $percentMatch = preg_match('/(\d+)\s*%/', $discountText, $matches);
        $codePercentMatch = preg_match('/(?:SALE|GIAM|OFF)?(\d+)/i', $voucherCode, $codeMatches);
        if ($percentMatch) {
            $pct = $matches[0];
            return "Get " . $pct . " off your purchase when you apply promo code " . $voucherCode . " at checkout.";
        } elseif ($codePercentMatch && (strpos($codeUpper, 'SALE') !== false || strpos($codeUpper, 'GIAM') !== false || strpos($codeUpper, 'OFF') !== false || preg_match('/^[A-Z]+\d+$/', $codeUpper))) {
            $pct = $codeMatches[1] . '%';
            return "Get " . $pct . " off your purchase when you apply promo code " . $voucherCode . " at checkout.";
        }

        // 3. Amount/Cash Discount (e.g. 100k, 50k, 50.000đ, 10$)
        $amountMatch = preg_match('/(\d+(?:\.\d+)?)\s*(?:k|K|đ|Đ|d|D|vnd|VND|đồng|dong|\$)/u', $discountText) || preg_match('/\d+[\d\.,]*/', $discountText);
        if ($amountMatch) {
            return "Enjoy a discount of " . $discountText . " on your order. Use code " . $voucherCode . " during checkout to redeem.";
        }

        // 4. Welcome / New user
        if (preg_match('/WELCOME|NEW|NEWUSER|NEW_USER|MEMBER|DANGKY|REG/', $codeUpper) || preg_match('/mới|thành viên mới|đăng ký|new|welcome/', $discountLower)) {
            $suffix = !empty($discountText) ? " (" . $discountText . ")" : "";
            return "Special welcome offer for new customers. Use code " . $voucherCode . " to save" . $suffix . " on your first order.";
        }

        // 5. Buy 1 Get 1 / Combo
        if (preg_match('/BUY1|GET1|BOGO|COMBO|MUA1|TANG1/', $codeUpper) || preg_match('/mua\s*1\s*tặng\s*1|combo|tặng|buy\s*1|get\s*1/', $discountLower)) {
            $suffix = !empty($discountText) ? " (" . $discountText . ")" : "";
            return "Exclusive Buy 1 Get 1 / Combo deal" . $suffix . ". Enter code " . $voucherCode . " in your cart to claim.";
        }

        // Fallback
        if (!empty($discountText)) {
            return "Use code " . $voucherCode . " at checkout to receive " . $discountText . " off your order.";
        } else {
            return "Apply promo code " . $voucherCode . " at checkout to unlock exclusive deals and savings.";
        }
    }
}

