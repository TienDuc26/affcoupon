        </main>
    </div>

    <!-- Footer -->
    <footer class="footer-premium">
        <div class="container">
            <div class="row g-5">
                <!-- Info Column -->
                <div class="col-lg-5 col-md-12">
                    <h5 class="footer-logo">
                        <i class="fa-solid fa-fire text-success"></i> Peak Vouch
                    </h5>
                    <p class="pe-lg-5">
                        Peak Vouch là nền tảng tổng hợp mã giảm giá, khuyến mãi và các ưu đãi affiliate tốt nhất từ các thương hiệu uy tín hàng đầu. Chúng tôi giúp người dùng tiết kiệm tối đa thời gian và tiền bạc khi mua sắm trực tuyến.
                    </p>
                </div>
                
                <!-- Links Column -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h5 class="footer-title">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="/blog">Tin tức Blog</a></li>
                        <li><a href="/home/about">Về chúng tôi</a></li>
                        <li><a href="/home/contact">Liên hệ</a></li>
                    </ul>
                </div>
                
                <!-- Social Column -->
                <div class="col-lg-4 col-md-6 col-6">
                    <h5 class="footer-title">Kết nối với chúng tôi</h5>
                    <div class="social-links mb-4">
                        <a href="https://facebook.com" class="social-icon" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com" class="social-icon" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://instagram.com" class="social-icon" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://pinterest.com" class="social-icon" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                    </div>
                    <div>
                        <a href="#" class="d-block mb-2 text-decoration-none text-muted small">Điều khoản sử dụng</a>
                        <a href="#" class="d-block text-decoration-none text-muted small">Chính sách bảo mật</a>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 small text-muted">&copy; <?php echo date('Y'); ?> Peak Vouch. Bảo lưu mọi quyền.</p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <p class="mb-0 small text-muted">Thiết kế bởi <a href="#" class="text-success text-decoration-none">Peak Vouch Team</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(window).on("load", function() {
            $('body').addClass('loaded');
        });

        // Initialize Bootstrap Tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // Toast Notification System
        function showToast(message, isError = false) {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.cssText = 'position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            toast.className = 'toast-premium';
            if (isError) {
                toast.style.borderLeftColor = '#ef4444';
            }
            
            const icon = isError ? 'fa-triangle-exclamation text-danger' : 'fa-circle-check text-success';
            toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${message}</span>`;
            
            container.appendChild(toast);
            
            // Force reflow
            toast.offsetHeight;
            
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Toggle Favorite
        function toggleFavorite(btn, couponId) {
            if (btn.disabled) return;
            btn.disabled = true;
            
            const icon = btn.querySelector('i');
            
            $.ajax({
                url: '/api/favorite/toggle',
                type: 'POST',
                data: JSON.stringify({ coupon_id: couponId }),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(response) {
                    btn.disabled = false;
                    if (response.success) {
                        btn.classList.add('active');
                        if (response.active) {
                            icon.className = 'fa-solid fa-heart text-danger';
                            showToast('Đã thêm vào yêu thích');
                        } else {
                            icon.className = 'fa-regular fa-heart';
                            showToast('Đã bỏ khỏi yêu thích');
                        }
                    } else if (response.code === 'UNAUTHORIZED') {
                        alert(response.message);
                    } else {
                        showToast(response.message || 'Đã có lỗi xảy ra', true);
                    }
                },
                error: function() {
                    btn.disabled = false;
                    showToast('Không thể kết nối đến máy chủ', true);
                }
            });
        }

        // Toggle Bookmark
        function toggleBookmark(btn, couponId) {
            if (btn.disabled) return;
            btn.disabled = true;
            
            const icon = btn.querySelector('i');
            
            $.ajax({
                url: '/api/bookmark/toggle',
                type: 'POST',
                data: JSON.stringify({ coupon_id: couponId }),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(response) {
                    btn.disabled = false;
                    if (response.success) {
                        btn.classList.add('active');
                        if (response.active) {
                            icon.className = 'fa-solid fa-bookmark text-success';
                            showToast('Đã lưu');
                        } else {
                            icon.className = 'fa-regular fa-bookmark';
                            showToast('Đã bỏ lưu');
                        }
                    } else if (response.code === 'UNAUTHORIZED') {
                        alert(response.message);
                    } else {
                        showToast(response.message || 'Đã có lỗi xảy ra', true);
                    }
                },
                error: function() {
                    btn.disabled = false;
                    showToast('Không thể kết nối đến máy chủ', true);
                }
            });
        }

        // Toggle Share Menu
        function toggleShareMenu(btn, couponId, url, title) {
            event.stopPropagation();
            
            // If browser supports native share
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: 'Xem mã giảm giá hấp dẫn này trên Peak Vouch!',
                    url: url
                }).catch((error) => console.log('Error sharing:', error));
                return;
            }
            
            const wrapper = btn.closest('.share-dropdown-wrapper');
            const menu = wrapper.querySelector('.custom-share-menu');
            
            // Hide other menus
            document.querySelectorAll('.custom-share-menu').forEach(m => {
                if (m !== menu) m.classList.add('d-none');
            });
            
            menu.classList.toggle('d-none');
        }

        // Close share menus when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.custom-share-menu').forEach(menu => {
                menu.classList.add('d-none');
            });
        });

        // Share Action handler
        function shareAction(event, type, url, title = '') {
            event.preventDefault();
            event.stopPropagation();
            
            const encodedUrl = encodeURIComponent(url);
            const encodedTitle = encodeURIComponent(title || 'Mã giảm giá cực hot!');
            
            let shareLink = '';
            
            switch (type) {
                case 'copy':
                    navigator.clipboard.writeText(url).then(() => {
                        showToast('Đã sao chép liên kết');
                    }).catch(() => {
                        // Fallback
                        const el = document.createElement('textarea');
                        el.value = url;
                        document.body.appendChild(el);
                        el.select();
                        document.execCommand('copy');
                        document.body.removeChild(el);
                        showToast('Đã sao chép liên kết');
                    });
                    break;
                case 'facebook':
                    shareLink = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
                    window.open(shareLink, '_blank', 'width=600,height=400');
                    break;
                case 'twitter':
                    shareLink = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`;
                    window.open(shareLink, '_blank', 'width=600,height=400');
                    break;
                case 'whatsapp':
                    shareLink = `https://api.whatsapp.com/send?text=${encodedTitle}%20${encodedUrl}`;
                    window.open(shareLink, '_blank', 'width=600,height=400');
                    break;
                case 'telegram':
                    shareLink = `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`;
                    window.open(shareLink, '_blank', 'width=600,height=400');
                    break;
            }
            
            // Close menu
            const menu = event.target.closest('.custom-share-menu');
            if (menu) menu.classList.add('d-none');
        }
    </script>
</body>
</html>

