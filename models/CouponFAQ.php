<?php
require_once __DIR__ . '/../config/database.php';

class CouponFAQ {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByCouponId($couponId) {
        $stmt = $this->db->prepare("SELECT * FROM coupon_faqs WHERE coupon_id = ?");
        $stmt->execute([$couponId]);
        return $stmt->fetchAll();
    }

    public function add($couponId, $question, $answer) {
        $stmt = $this->db->prepare("INSERT INTO coupon_faqs (coupon_id, question, answer) VALUES (?, ?, ?)");
        return $stmt->execute([$couponId, $question, $answer]);
    }

    public function deleteByCouponId($couponId) {
        $stmt = $this->db->prepare("DELETE FROM coupon_faqs WHERE coupon_id = ?");
        return $stmt->execute([$couponId]);
    }
}
