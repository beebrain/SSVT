<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>ผลงานของฉัน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        body { background: #f5f7fa; }
        .header-bar {
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            color: #fff;
            padding: 20px;
        }
        .assignment-card {
            border-radius: 16px;
            border: 2px solid #e0e0e0;
            transition: all 0.2s;
            overflow: hidden;
        }
        .assignment-card.submitted { border-color: #4caf50; }
        .assignment-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .status-badge-submitted { background: #e8f5e9; color: #2e7d32; }
        .status-badge-pending { background: #fff3e0; color: #e65100; }
    </style>
</head>
<body>
<!-- Header -->
<div class="header-bar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="small opacity-75">ยินดีต้อนรับ</div>
                <h5 class="mb-0 fw-bold"><?= esc($participant['name']) ?></h5>
                <div class="small opacity-75">รหัส: <?= esc($participant['participant_code']) ?></div>
            </div>
            <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-light btn-outline-light text-white">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Summary -->
    <?php
        $submittedCount = count(array_filter($assignments, fn($a) => $a['submitted']));
        $total = count($assignments);
    ?>
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold">ความคืบหน้า</span>
                <span class="fw-bold text-primary"><?= $submittedCount ?>/<?= $total ?> งาน</span>
            </div>
            <div class="progress" style="height:10px;border-radius:5px;">
                <div class="progress-bar bg-success" style="width:<?= $total > 0 ? round($submittedCount/$total*100) : 0 ?>%"></div>
            </div>
        </div>
    </div>

    <h6 class="fw-semibold text-muted mb-3">ผลงานทั้งหมด</h6>

    <div class="row g-3">
        <?php foreach ($assignments as $a): ?>
        <div class="col-12">
            <div class="assignment-card bg-white p-0 <?= $a['submitted'] ? 'submitted' : '' ?>">
                <div class="p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:50px;height:50px;background:<?= $a['submitted'] ? '#e8f5e9' : '#f5f5f5' ?>;">
                        <i class="bi bi-<?= $a['submitted'] ? 'check-circle-fill text-success' : 'circle text-muted' ?>" style="font-size:1.4rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= esc($a['title']) ?></div>
                        <?php if (!empty($a['description'])): ?>
                        <div class="text-muted small"><?= esc($a['description']) ?></div>
                        <?php endif; ?>
                        <?php if ($a['submitted']): ?>
                        <span class="badge status-badge-submitted small">
                            <i class="bi bi-check2 me-1"></i>ส่งแล้ว <?= $a['file_count'] ?? 0 ?> ไฟล์
                        </span>
                        <?php else: ?>
                        <span class="badge status-badge-pending small">
                            <i class="bi bi-clock me-1"></i>ยังไม่ส่ง
                        </span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('submit/' . $a['id']) ?>" class="btn btn-sm <?= $a['submitted'] ? 'btn-outline-success' : 'btn-primary' ?> flex-shrink-0">
                        <?= $a['submitted'] ? '<i class="bi bi-pencil me-1"></i>แก้ไข' : '<i class="bi bi-upload me-1"></i>ส่งผลงาน' ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($assignments)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-inbox display-4 d-block mb-2"></i>ยังไม่มีผลงานที่ต้องส่ง
        </div>
        <?php endif; ?>
    </div>

    <!-- WordCloud shortcut -->
    <div class="card border-0 shadow-sm mt-3" style="border-radius:16px;">
        <div class="card-body py-3 d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:46px;height:46px;background:#e3f2fd;">
                <i class="bi bi-cloud-fill text-primary" style="font-size:1.3rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold">WordCloud</div>
                <div class="text-muted small">ร่วมแชร์ความคิดเห็นของคุณ</div>
            </div>
            <a href="<?= base_url('wordcloud') ?>" class="btn btn-sm btn-outline-primary flex-shrink-0">
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
