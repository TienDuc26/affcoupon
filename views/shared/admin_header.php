<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Store Management - Admin Panel</title>
    <link rel="icon" type="image/png" href="/favicon.png" />
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="/fontawesome/css/all.min.css" />
    <!-- ADMIN CSS -->
    <link rel="stylesheet" href="/css/admin.css" />
</head>
<body>

    <div class="admin-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo d-flex justify-content-between align-items-center w-100">
                <a href="/" style="color: white; text-decoration: none;">Coupon Admin</a>
                <button class="btn btn-link text-white d-lg-none close-sidebar-btn" style="padding: 0 10px; font-size: 20px; border: none; text-decoration: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="menu-title">
                QUẢN LÝ NỘI DUNG
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="/admin/categorymanagement">
                        <span>
                            <i class="fas fa-list"></i>
                            Quản lý danh mục
                        </span>
                    </a>
                </li>

                <!-- SUBMENU -->
                <li class="has-submenu open">
                    <a href="javascript:void(0)" class="submenu-toggle">
                        <span>
                            <i class="fas fa-tags"></i>
                            Quản lý coupon
                        </span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/admin/dashboardadmin">Store</a>
                        </li>
                        <li>
                            <a href="/admin/offermanagement">Offer</a>
                        </li>
                    </ul>
                </li>
                
                <!-- BLOG SUBMENU -->
                <li class="has-submenu open">
                    <a href="javascript:void(0)" class="submenu-toggle">
                        <span>
                            <i class="fas fa-newspaper"></i>
                            Quản lý Blog
                        </span>
                        <i class="fas fa-chevron-down arrow"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/admin/blogposts">Bài viết</a>
                        </li>
                        <li>
                            <a href="/admin/blogcategories">Danh mục</a>
                        </li>
                        <li>
                            <a href="/admin/blogtags">Tags</a>
                        </li>
                        <li>
                            <a href="/admin/medialibrary">Thư viện Media</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="/admin/usermanagement">
                        <span>
                            <i class="fas fa-users"></i>
                            Quản lý User
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="main-content">
            <!-- TOPBAR -->
            <div class="topbar d-flex justify-content-between align-items-center">
                <div class="topbar-left">
                    <button class="menu-btn btn btn-light btn-sm">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="topbar-right d-flex align-items-center gap-3">
                    <span class="mr-3 text-muted">Xin chào, <strong><?php echo htmlspecialchars($username); ?></strong></span>
                    <a href="/" class="btn btn-outline-secondary btn-sm mr-2"><i class="fas fa-home"></i> Xem Trang chủ</a>
                    <a href="/account/logout" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                </div>
            </div>

            <!-- PAGE INNER CONTENT -->
            <div class="page-content container-fluid">
                <!-- Session messages -->
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
