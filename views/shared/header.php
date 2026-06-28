<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($seoTitle) && !empty($seoTitle) ? htmlspecialchars($seoTitle) : (isset($post['seo_title']) && !empty($post['seo_title']) ? htmlspecialchars($post['seo_title']) : (isset($post['title']) ? htmlspecialchars($post['title']) . ' - Peak Vouch' : 'Peak Vouch')); ?></title>
    
    <?php 
        $metaDescription = $seoDescription ?? $post['seo_description'] ?? $post['summary'] ?? 'Peak Vouch - Cung cấp mã giảm giá hot, deal khuyến mãi, và blog chia sẻ kiến thức hữu ích.';
    ?>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    
    <?php if (isset($post['canonical_url']) && !empty($post['canonical_url'])): ?>
        <link rel="canonical" href="<?php echo htmlspecialchars($post['canonical_url']); ?>">
    <?php else: ?>
        <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>">
    <?php endif; ?>
    
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo htmlspecialchars($post['seo_title'] ?? $post['title'] ?? 'Peak Vouch'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($post['thumbnail'] ?? '/img/banner.jpg')); ?>">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>">
    <meta property="og:site_name" content="Peak Vouch">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($post['seo_title'] ?? $post['title'] ?? 'Peak Vouch'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($post['thumbnail'] ?? '/img/banner.jpg')); ?>">

    <!-- JSON-LD Structured Data Schema -->
    <?php if (isset($post['id']) && isset($post['author_name'])): ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>"
            },
            "headline": "<?php echo htmlspecialchars($post['title']); ?>",
            "description": "<?php echo htmlspecialchars($metaDescription); ?>",
            "image": "<?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($post['thumbnail'] ?? '/img/banner.jpg')); ?>",  
            "author": {
                "@type": "Person",
                "name": "<?php echo htmlspecialchars($post['author_name']); ?>"
            },  
            "publisher": {
                "@type": "Organization",
                "name": "Peak Vouch",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/img/Logo.jpg'; ?>"
                }
            },
            "datePublished": "<?php echo date('Y-m-d\TH:i:sP', strtotime($post['published_at'] ?? $post['created_at'])); ?>",
            "dateModified": "<?php echo date('Y-m-d\TH:i:sP', strtotime($post['updated_at'] ?? $post['published_at'] ?? $post['created_at'])); ?>"
        }
        </script>

        <!-- Breadcrumb Schema -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "Trang chủ",
                "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/'; ?>"
            },{
                "@type": "ListItem",
                "position": 2,
                "name": "Blog",
                "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/blog'; ?>"
            },{
                "@type": "ListItem",
                "position": 3,
                "name": "<?php echo htmlspecialchars($post['category_name'] ?? 'Chung'); ?>",
                "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/blog?categoryId=' . ($post['category_id'] ?? ''); ?>"
            },{
                "@type": "ListItem",
                "position": 4,
                "name": "<?php echo htmlspecialchars($post['title']); ?>"
            }]
        }
        </script>
    <?php endif; ?>

    <link rel="icon" type="image/png" href="/favicon.png">
    
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="/css/design-system.css">
</head>
<body>
    <!-- Page Loader -->
    <div id="loader-wrapper">
        <div class="spinner-premium"></div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-premium sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fa-solid fa-fire text-success"></i>
                Peak Vouch
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-secondary"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php 
                    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $isBlog = (strpos($currentPath, '/blog') === 0);
                    $isHome = ($currentPath === '/' || $currentPath === '/index' || $currentPath === '/home' || $currentPath === '/home/index');
                    $isAbout = ($currentPath === '/home/about');
                    $isContact = ($currentPath === '/home/contact');
                ?>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $isHome ? 'active' : ''; ?>" aria-current="page" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Danh mục
                        </a>
                        <ul class="dropdown-menu border-0 shadow" aria-labelledby="categoryDropdown">
                            <li><a class="dropdown-item" href="/">Tất cả danh mục</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <li>
                                        <a class="dropdown-item" href="/?categoryId=<?php echo $cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $isBlog ? 'active' : ''; ?>" href="/blog">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $isAbout ? 'active' : ''; ?>" href="/home/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $isContact ? 'active' : ''; ?>" href="/home/contact">Liên hệ</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <?php if (empty($username)): ?>
                        <a href="/account/login" class="btn btn-light btn-pill px-4">
                            Đăng nhập
                        </a>
                        <a href="/account/register" class="btn btn-success btn-pill px-4">
                            Đăng ký
                        </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle btn-pill"
                                    type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($username); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow" aria-labelledby="userMenuBtn">
                                <?php if ($roleId == 1): ?>
                                    <li>
                                        <a class="dropdown-item text-success fw-bold" href="/admin/dashboardadmin">
                                            <i class="fas fa-cog me-2"></i> Quản trị Admin
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item" href="/profile">
                                        <i class="fas fa-user-circle me-2 text-primary"></i> Trang cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/account/favorites">
                                        <i class="fas fa-heart me-2 text-danger"></i> Coupon yêu thích
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/account/bookmarks">
                                        <i class="fas fa-bookmark me-2 text-success"></i> Coupon đã lưu
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/account/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Compact Search Banner (Only displayed if not on home page) -->
    <?php if (!$isHome): ?>
        <div class="bg-white border-bottom py-3">
            <div class="container d-flex justify-content-center">
                <form class="d-flex w-100" action="/" method="GET" style="max-width: 600px;">
                    <div class="input-group input-group-premium">
                        <input type="text" name="keyword" class="form-control border-0" placeholder="Tìm kiếm mã giảm giá, thương hiệu..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" required>
                        <button class="btn btn-success border-0 px-4" type="submit">
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Container -->
    <div class="container mt-5">
        <main role="main" class="pb-5">

