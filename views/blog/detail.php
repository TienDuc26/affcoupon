<!-- BREADCRUMBS -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-success text-decoration-none fw-semibold">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/blog" class="text-success text-decoration-none fw-semibold">Blog</a></li>
        <?php if (!empty($post['category_name'])): ?>
            <li class="breadcrumb-item"><a href="/blog?categoryId=<?php echo $post['category_id']; ?>" class="text-success text-decoration-none fw-semibold"><?php echo htmlspecialchars($post['category_name']); ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- MAIN CONTENT -->
    <div class="col-lg-8">
        <article class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <h1 class="display-5 fw-bold text-dark mb-3" style="line-height: 1.3; letter-spacing: -0.03em;">
                <?php echo htmlspecialchars($post['title']); ?>
            </h1>
            
            <div class="post-meta-details mb-4 d-flex flex-wrap gap-3 text-muted small border-bottom pb-3">
                <span><i class="far fa-user text-success me-1"></i> Đăng bởi: <strong><?php echo htmlspecialchars($post['author_name'] ?: 'Peak Vouch'); ?></strong></span>
                <span><i class="far fa-calendar-alt text-success me-1"></i> <?php echo date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])); ?></span>
                <span><i class="far fa-clock text-success me-1"></i> <?php echo htmlspecialchars($post['reading_time'] ?: '5 phút đọc'); ?></span>
                <span><i class="far fa-eye text-success me-1"></i> <?php echo $post['view_count']; ?> lượt xem</span>
            </div>

            <!-- Hero Image -->
            <?php if (!empty($post['thumbnail'])): ?>
                <div class="rounded-4 overflow-hidden mb-4 shadow-sm">
                    <img src="<?php echo htmlspecialchars($post['thumbnail']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid w-100" style="max-height: 450px; object-fit: cover;" />
                </div>
            <?php endif; ?>

            <!-- Table of contents inline (mobile layout helper) -->
            <div class="d-lg-none mb-4">
                <div class="toc-container">
                    <div class="toc-title">
                        <i class="fas fa-list-ul"></i> Mục lục bài viết
                    </div>
                    <ul class="toc-list" id="toc-list-mobile">
                        <!-- Populated via JS -->
                    </ul>
                </div>
            </div>

            <!-- Content Body -->
            <div class="post-content-body text-secondary mb-5" id="post-content" style="line-height: 1.9; font-size: 1.05rem;">
                <?php echo $post['content']; ?>
            </div>

            <!-- Tags list -->
            <?php if (!empty($tags)): ?>
                <div class="mb-4 pt-3 border-top">
                    <span class="fw-bold text-dark me-2 small">Tags:</span>
                    <div class="d-inline-flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag): ?>
                            <a href="/blog?tagId=<?php echo $tag['id']; ?>" class="btn btn-light btn-pill btn-sm px-3 py-1">
                                #<?php echo htmlspecialchars($tag['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Social share section -->
            <div class="my-5 p-4 bg-light rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="fw-bold text-dark small">Chia sẻ bài viết:</div>
                <div class="d-flex gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-primary btn-pill btn-sm px-3 d-flex align-items-center gap-2" style="background-color: #3b5998; border: none;">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="btn btn-info btn-pill btn-sm px-3 d-flex align-items-center gap-2 text-white" style="background-color: #1da1f2; border: none;">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <button type="button" class="btn btn-success btn-pill btn-sm px-3 d-flex align-items-center gap-2" onclick="copyPostLink(this)">
                        <i class="fas fa-link"></i> Sao chép link
                    </button>
                </div>
            </div>

            <!-- Previous / Next post nav -->
            <div class="row border-top border-bottom py-4 my-4 g-3">
                <div class="col-md-6 d-flex flex-column align-items-start border-end pe-md-4">
                    <?php if (!empty($prevPost)): ?>
                        <small class="text-muted text-uppercase mb-1"><i class="fas fa-arrow-left"></i> Bài trước đó</small>
                        <a href="/blog/<?php echo htmlspecialchars($prevPost['slug']); ?>" class="text-success fw-bold text-decoration-none">
                            <?php echo htmlspecialchars($prevPost['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-end text-end ps-md-4">
                    <?php if (!empty($nextPost)): ?>
                        <small class="text-muted text-uppercase mb-1">Bài tiếp theo <i class="fas fa-arrow-right"></i></small>
                        <a href="/blog/<?php echo htmlspecialchars($nextPost['slug']); ?>" class="text-success fw-bold text-decoration-none">
                            <?php echo htmlspecialchars($nextPost['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-success text-white p-4 p-md-5 rounded-4 text-center mt-5" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important; box-shadow: 0 10px 30px rgba(22,163,74,0.15);">
                <h3 class="fw-bold text-white mb-2">Đăng ký nhận tin tức ưu đãi</h3>
                <p class="text-white-50 mb-4 max-width-500 mx-auto">Bạn có muốn nhận trực tiếp các mã giảm giá và deals độc quyền mới nhất qua hòm thư?</p>
                <a href="/home/contact" class="btn btn-light btn-pill px-4 py-2">Liên hệ & Đăng ký nhận tin</a>
            </div>
        </article>
    </div>

    <!-- STICKY SIDEBAR -->
    <div class="col-lg-4">
        <div class="sticky-sidebar">
            <!-- Table of Contents widget for Desktop -->
            <div class="d-none d-lg-block mb-4">
                <div class="toc-container">
                    <div class="toc-title">
                        <i class="fas fa-list-ul"></i> Mục lục bài viết
                    </div>
                    <ul class="toc-list" id="toc-list-desktop">
                        <!-- Populated via JS -->
                    </ul>
                </div>
            </div>

            <!-- Related Posts widget -->
            <?php if (!empty($relatedPosts)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Bài viết liên quan</h5>
                    
                    <?php foreach ($relatedPosts as $rel): ?>
                        <div class="d-flex mb-3 align-items-center">
                            <div class="rounded-3 overflow-hidden me-3" style="width: 80px; height: 60px; min-width: 80px; background-color: #f1f5f9;">
                                <a href="/blog/<?php echo htmlspecialchars($rel['slug']); ?>">
                                    <img src="<?php echo htmlspecialchars($rel['thumbnail'] ?: '/img/banner.jpg'); ?>" class="w-100 h-100" style="object-fit: cover;" />
                                </a>
                            </div>
                            <div class="min-width-0">
                                <a href="/blog/<?php echo htmlspecialchars($rel['slug']); ?>" class="text-dark text-decoration-none fw-semibold d-block text-truncate" style="font-size: 0.9rem; line-height: 1.4;" title="<?php echo htmlspecialchars($rel['title']); ?>">
                                    <?php echo htmlspecialchars($rel['title']); ?>
                                </a>
                                <small class="text-muted" style="font-size:0.75rem;"><i class="far fa-calendar-alt text-success me-1"></i><?php echo date('d/m/Y', strtotime($rel['published_at'] ?? $rel['created_at'])); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Copy link function helper
    function copyPostLink(btn) {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Đã chép!';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
            }, 1500);
        }).catch(err => {
            console.error('Failed to copy link: ', err);
        });
    }

    // Dynamic Table of Contents Generation
    window.addEventListener('DOMContentLoaded', () => {
        const contentArea = document.getElementById('post-content');
        if (!contentArea) return;

        // Query all h2 and h3 elements inside content
        const headings = contentArea.querySelectorAll('h2, h3');
        const desktopList = document.getElementById('toc-list-desktop');
        const mobileList = document.getElementById('toc-list-mobile');

        if (headings.length === 0) {
            // Hide TOC containers if no headings exist
            const tocContainers = document.querySelectorAll('.toc-container');
            tocContainers.forEach(c => c.style.display = 'none');
            return;
        }

        headings.forEach((heading, index) => {
            // Generate clean ID if none exists
            if (!heading.id) {
                heading.id = 'heading-' + index;
            }

            // Create list items
            const li = document.createElement('li');
            li.className = 'toc-item';
            
            const link = document.createElement('a');
            link.href = '#' + heading.id;
            link.className = 'toc-link depth-' + heading.tagName.substring(1);
            link.textContent = heading.textContent;
            
            li.appendChild(link);

            // Populate both desktop and mobile lists
            if (desktopList) {
                desktopList.appendChild(li.cloneNode(true));
            }
            if (mobileList) {
                mobileList.appendChild(li);
            }
        });
    });
</script>

