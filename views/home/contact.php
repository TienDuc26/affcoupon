<div class="py-5">
    <div class="row g-5">
        <!-- Contact Form Column -->
        <div class="col-lg-5 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h3 class="fw-bold mb-3 text-success">Gửi lời nhắn</h3>
                <p class="text-muted mb-4">Vui lòng điền thông tin vào biểu mẫu dưới đây để gửi ý kiến đóng góp hoặc yêu cầu hỗ trợ.</p>
                
                <form id="contact-form" action="" method="POST" class="needs-validation">
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark">Họ và tên</label>
                        <input type="text" name="name" class="form-control" placeholder="Nguyễn Văn A" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark">Địa chỉ Email</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 text-dark">Chủ đề cần tư vấn</label>
                        <select class="form-select" id="contact-select" name="inquiry">
                            <option value="-">Chọn chủ đề</option>
                            <option value="sales">Hợp tác & Tiếp thị</option>
                            <option value="creative">Thiết kế sáng tạo</option>
                            <option value="uiux">Trải nghiệm UI / UX</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-600 text-dark">Nội dung liên hệ</label>
                        <textarea rows="6" name="message" class="form-control" placeholder="Nhập nội dung lời nhắn..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3">Gửi liên hệ</button>
                </form>
            </div>
        </div>

        <!-- Address Info Column -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h3 class="fw-bold mb-4 text-success">Thông tin</h3>
                <p class="text-muted mb-4">Để nhận được phản hồi nhanh nhất, bạn cũng có thể liên hệ trực tiếp qua hotline hoặc email của chúng tôi.</p>
                
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-1">Địa chỉ trụ sở:</h6>
                    <address class="text-secondary">
                        120-240 Fusce eleifend varius tempus<br>
                        Duis consectetur at ligula 10660
                    </address>
                </div>
                
                <div class="pt-2">
                    <h6 class="fw-bold text-dark mb-3">Thông tin liên lạc:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a href="mailto:info@company.com" class="text-decoration-none text-secondary d-flex align-items-center gap-2">
                                <i class="fas fa-envelope text-success"></i> info@company.com
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="tel:0100200340" class="text-decoration-none text-secondary d-flex align-items-center gap-2">
                                <i class="fas fa-phone text-success"></i> 010-020-0340
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none text-secondary d-flex align-items-center gap-2">
                                <i class="fas fa-globe text-success"></i> www.company.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Map Column -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h3 class="fw-bold mb-4 text-success">Bản đồ</h3>
                <div class="rounded-3 overflow-hidden border" style="height: 380px;">
                    <iframe width="100%" height="100%" id="gmap-canvas"
                            src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members section -->
    <div class="row g-4 pt-5 mt-4">
        <div class="col-12 text-center mb-4">
            <span class="text-success fw-bold small text-uppercase">Thành viên sáng lập</span>
            <h2 class="fw-bold mt-2">Gặp gỡ ban quản trị</h2>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <img src="/img/people-1.jpg" alt="Ryan White" class="mb-4 img-fluid rounded-circle mx-auto" style="width: 120px; height: 120px; object-fit: cover;">
                <h5 class="fw-bold text-dark mb-1">Ryan White</h5>
                <h6 class="text-success small mb-3">Chief Executive Officer</h6>
                <p class="text-muted small mb-4">
                    Người định hướng chiến lược phát triển sản phẩm của Peak Vouch, kết nối các đối tác doanh nghiệp lớn.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <img src="/img/people-2.jpg" alt="Catherine Pinky" class="mb-4 img-fluid rounded-circle mx-auto" style="width: 120px; height: 120px; object-fit: cover;">
                <h5 class="fw-bold text-dark mb-1">Catherine Pinky</h5>
                <h6 class="text-success small mb-3">Chief Marketing Officer</h6>
                <p class="text-muted small mb-4">
                    Đảm nhận toàn bộ chiến dịch quảng bá hình ảnh sản phẩm và nội dung chia sẻ cộng đồng.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <img src="/img/people-3.jpg" alt="Johnny Brief" class="mb-4 img-fluid rounded-circle mx-auto" style="width: 120px; height: 120px; object-fit: cover;">
                <h5 class="fw-bold text-dark mb-1">Johnny Brief</h5>
                <h6 class="text-success small mb-3">Accounting Executive</h6>
                <p class="text-muted small mb-4">
                    Giám sát ngân sách, chi phí chiến dịch tiếp thị và quản lý quan hệ đối tác hoàn tiền.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                <img src="/img/people-4.jpg" alt="George Nelson" class="mb-4 img-fluid rounded-circle mx-auto" style="width: 120px; height: 120px; object-fit: cover;">
                <h5 class="fw-bold text-dark mb-1">George Nelson</h5>
                <h6 class="text-success small mb-3">Creative Art Director</h6>
                <p class="text-muted small mb-4">
                    Chịu trách nhiệm về thiết kế giao diện, trải nghiệm hình ảnh thương hiệu của hệ thống website.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-light btn-pill btn-sm px-3"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

