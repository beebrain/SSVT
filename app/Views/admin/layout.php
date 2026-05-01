<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SSVT App') ?> - ระบบจัดการผลงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f5f7fa; }
        .sidebar { width: 250px; min-height: 100vh; background: linear-gradient(180deg, #1a237e 0%, #283593 100%); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 10px 20px; border-radius: 8px; margin: 2px 8px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.15); }
        .sidebar .nav-link i { width: 20px; }
        .sidebar-brand { color: #fff; font-size: 1.1rem; font-weight: 700; padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.15); }
        .main-content { flex: 1; }
        .page-header { background: #fff; border-bottom: 1px solid #e0e0e0; padding: 15px 25px; }
        .content-area { padding: 25px; }
        @media (max-width: 768px) {
            .sidebar { display: none; }
        }
        .badge-submitted { background: #4caf50; }
        .badge-pending { background: #ff9800; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-none d-md-flex flex-column">
        <div class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2"></i>SSVT App
        </div>
        <ul class="nav flex-column mt-2 flex-grow-1">
            <li class="nav-item">
                <a class="nav-link <?= uri_string() === 'admin/dashboard' || uri_string() === 'admin' ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= str_contains(uri_string(), 'admin/participants') ? 'active' : '' ?>" href="<?= base_url('admin/participants') ?>">
                    <i class="bi bi-people-fill me-2"></i>ผู้เข้าอบรม
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= str_contains(uri_string(), 'admin/assignments') ? 'active' : '' ?>" href="<?= base_url('admin/assignments') ?>">
                    <i class="bi bi-journal-text me-2"></i>ผลงาน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= str_contains(uri_string(), 'admin/submissions') ? 'active' : '' ?>" href="<?= base_url('admin/submissions') ?>">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>ภาพรวมการส่งงาน
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= str_contains(uri_string(), 'admin/wordcloud') ? 'active' : '' ?>" href="<?= base_url('admin/wordcloud') ?>">
                    <i class="bi bi-cloud-fill me-2"></i>WordCloud
                </a>
            </li>
            <li class="nav-item mt-auto">
                <hr class="border-secondary">
                <a class="nav-link" href="<?= base_url('wordcloud/display') ?>" target="_blank">
                    <i class="bi bi-tv me-2"></i>แสดง WordCloud
                </a>
                <hr class="border-secondary my-1">
                <div class="px-3 py-1 text-white-50 small">
                    <i class="bi bi-person-circle me-1"></i><?= session()->get('admin_username') ?? 'Admin' ?>
                </div>
                <a class="nav-link text-warning" href="<?= base_url('admin/logout') ?>">
                    <i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main -->
    <div class="main-content">
        <!-- Top bar (mobile) -->
        <div class="page-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold"><?= esc($title ?? 'หน้าหลัก') ?></h5>
            </div>
        </div>

        <!-- Alerts -->
        <div class="content-area pb-0">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>

        <div class="content-area">
            <?= $content ?>
        </div>
    </div>
</div>

<!-- Mobile sidebar -->
<div class="offcanvas offcanvas-start" id="mobileSidebar">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title"><i class="bi bi-mortarboard-fill me-2"></i>SSVT App</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column p-2">
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/participants') ?>"><i class="bi bi-people-fill me-2"></i>ผู้เข้าอบรม</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/assignments') ?>"><i class="bi bi-journal-text me-2"></i>ผลงาน</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/submissions') ?>"><i class="bi bi-grid-3x3-gap-fill me-2"></i>ภาพรวมการส่งงาน</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/wordcloud') ?>"><i class="bi bi-cloud-fill me-2"></i>WordCloud</a></li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
