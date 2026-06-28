<div class="container text-center my-5 py-5" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <h1 style="font-size: 5rem; color: #16A516; font-weight: 700; margin-bottom: 20px;">404</h1>
    <h3 class="mb-4" style="color: #333; font-weight: 600;">Không tìm thấy trang yêu cầu</h3>
    <p class="text-muted mb-4" style="max-width: 500px; font-size: 1.05rem;">
        <?php echo htmlspecialchars($message ?? 'Đường dẫn bạn yêu cầu không tồn tại hoặc đã bị xóa. Vui lòng quay lại trang chủ hoặc kiểm tra lại liên kết.'); ?>
    </p>
    <a href="/" class="btn btn-primary px-4 py-2 rounded-pill" style="background-color: #16A516 !important; border-color: #16A516 !important; font-weight: 600;">
        Quay lại Trang Chủ
    </a>
</div>
