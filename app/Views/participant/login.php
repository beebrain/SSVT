<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบส่งผลงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 50%, #1565c0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
        }
        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1a237e, #3949ab);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 2px;
            border: 2px solid #e0e0e0;
        }
        .form-control:focus { border-color: #3949ab; box-shadow: 0 0 0 3px rgba(57,73,171,0.2); }
        .btn-login {
            background: linear-gradient(135deg, #1a237e, #3949ab);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: transform 0.1s;
        }
        .btn-login:hover { transform: translateY(-2px); color: #fff; }
        .btn-login:active { transform: translateY(0); }
    </style>
</head>
<body>
<div class="login-card mx-3">
    <div class="brand-icon">
        <i class="bi bi-mortarboard-fill text-white" style="font-size:2rem;"></i>
    </div>
    <h4 class="text-center fw-bold mb-1">ผลงานการอบรม</h4>
    <p class="text-center text-muted small mb-4">กรอกรหัสผู้เข้าอบรม เพื่อเข้าสู่ระบบ</p>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center py-2">
        <i class="bi bi-exclamation-circle me-1"></i><?= esc($error) ?>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="form-label fw-semibold">รหัสผู้เข้าอบรม</label>
            <input type="text"
                   class="form-control"
                   name="participant_code"
                   placeholder="กรอกรหัสที่ได้รับ"
                   autocomplete="off"
                   autocapitalize="off"
                   autofocus
                   required>
        </div>
        <button type="submit" class="btn btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
        </button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
