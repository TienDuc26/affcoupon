<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký - Peak Vouch</title>
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary: #16a34a;
            --secondary: #0f172a;
            --bg-accent: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #334155;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            padding: 40px 0;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .auth-header {
            background-color: var(--secondary);
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .auth-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary), #22c55e);
        }

        .logo-text {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #ffffff;
            margin: 0;
        }

        .logo-text span {
            color: var(--primary);
        }

        .auth-body {
            padding: 2.5rem 2.25rem;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(22, 163, 74, 0.15);
        }

        .btn-success-premium {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-success-premium:hover {
            background-color: #15803d;
            border-color: #15803d;
            transform: translateY(-1px);
        }

        .auth-footer-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer-link:hover {
            color: #15803d;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <div class="auth-header">
            <h2 class="logo-text"><i class="fas fa-ticket-alt me-2 text-success"></i>Peak<span>Vouch</span></h2>
            <p class="text-white-50 small mb-0 mt-2">Đăng ký thành viên chia sẻ ưu đãi</p>
        </div>

        <div class="auth-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger border-0 small text-center mb-4 py-2" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/account/register">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Tên đăng nhập *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-user"></i></span>
                        <input type="text" name="Username" class="form-control border-start-0 ps-0" placeholder="Username" value="<?php echo htmlspecialchars($Username ?? ''); ?>" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Địa chỉ Email *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-envelope"></i></span>
                        <input type="email" name="Email" class="form-control border-start-0 ps-0" placeholder="email@domain.com" value="<?php echo htmlspecialchars($Email ?? ''); ?>" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Họ và tên</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-id-card"></i></span>
                        <input type="text" name="FullName" class="form-control border-start-0 ps-0" placeholder="Nguyễn Văn A" value="<?php echo htmlspecialchars($FullName ?? ''); ?>" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Mật khẩu *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                        <input type="password" name="Password" class="form-control border-start-0 ps-0" placeholder="••••••••" required />
                    </div>
                </div>

                <button type="submit" class="btn btn-success-premium w-100 mb-3">Đăng ký tài khoản</button>
            </form>

            <div class="text-center small text-muted mt-4">
                Đã có tài khoản? <a href="/account/login" class="auth-footer-link">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

</body>
</html>

