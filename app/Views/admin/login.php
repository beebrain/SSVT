<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 400px;
            padding: 40px 35px;
        }
        .brand-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #1a237e, #3949ab);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
        }
        .form-control:focus { border-color: #3949ab; box-shadow: 0 0 0 3px rgba(57,73,171,0.2); }
        .btn-login {
            background: linear-gradient(135deg, #1a237e, #3949ab);
            color: #fff; border: none; border-radius: 12px;
            padding: 13px; font-size: 1rem; font-weight: 600;
            width: 100%; transition: transform 0.1s;
        }
        .btn-login:hover { transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>
<div class="login-card mx-3">
    <div class="brand-icon">
        <i class="bi bi-shield-lock-fill text-white" style="font-size:2rem;"></i>
    </div>
    <h5 class="text-center fw-bold mb-1">ระบบจัดการผลงาน</h5>
    <p class="text-center text-muted small mb-4">เข้าสู่ระบบสำหรับผู้ดูแล</p>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 text-center small">
        <i class="bi bi-exclamation-circle me-1"></i><?= esc($error) ?>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold small">ชื่อผู้ใช้</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-2 border-end-0"><i class="bi bi-person text-muted"></i></span>
                <input type="text" class="form-control border-2 border-start-0" name="username" placeholder="admin" autocomplete="username" autofocus required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold small">รหัสผ่าน</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-2 border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" class="form-control border-2 border-start-0" name="password" placeholder="••••••••" autocomplete="current-password" required>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="<?= base_url('login') ?>" class="text-muted small text-decoration-none">
            <i class="bi bi-person-circle me-1"></i>หน้าผู้เข้าอบรม
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
