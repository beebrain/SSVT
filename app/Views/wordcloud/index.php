<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>WordCloud - พิมพ์คำของคุณ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        body {
            background: linear-gradient(135deg, #0d47a1 0%, #1976d2 50%, #42a5f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .wc-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 460px;
            padding: 36px 30px;
        }
        .word-input {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .word-input:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25,118,210,0.2);
        }
        .word-num {
            width: 30px; height: 30px;
            background: #e3f2fd;
            color: #1565c0;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }
        .btn-wc {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: #fff; border: none; border-radius: 14px;
            padding: 14px; font-size: 1.1rem; font-weight: 600;
            width: 100%; transition: transform 0.1s;
        }
        .btn-wc:hover { transform: translateY(-2px); color: #fff; }
        .success-banner {
            background: #e8f5e9;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="wc-card">
    <!-- Header -->
    <div class="text-center mb-4">
        <i class="bi bi-cloud-fill text-primary" style="font-size:3rem;"></i>
        <h4 class="fw-bold mb-1 mt-2">WordCloud</h4>
        <p class="text-muted small">พิมพ์ 3 คำที่นึกถึงเกี่ยวกับการอบรมนี้</p>
        <?php if ($total > 0): ?>
        <span class="badge bg-light text-primary border">
            <i class="bi bi-people me-1"></i><?= $total ?> คนร่วมแล้ว
        </span>
        <?php endif; ?>
    </div>

    <!-- Success flash -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="success-banner d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div>
            <div class="fw-semibold text-success small">บันทึกแล้ว!</div>
            <div class="text-muted small">คำของคุณถูกเพิ่มใน WordCloud เรียบร้อยแล้ว</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Error -->
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 small mb-3">
        <i class="bi bi-exclamation-circle me-1"></i><?= esc($error) ?>
    </div>
    <?php endif; ?>

    <!-- Form — always visible, ส่งได้ไม่จำกัดครั้ง -->
    <form action="<?= base_url('wordcloud') ?>" method="post">
        <?= csrf_field() ?>
        <?php
        $inputs = [
            ['num' => 1, 'placeholder' => 'คำที่ 1...'],
            ['num' => 2, 'placeholder' => 'คำที่ 2...'],
            ['num' => 3, 'placeholder' => 'คำที่ 3...'],
        ];
        ?>
        <?php foreach ($inputs as $w): ?>
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="word-num"><?= $w['num'] ?></div>
            <input type="text"
                   class="form-control word-input"
                   name="word<?= $w['num'] ?>"
                   placeholder="<?= $w['placeholder'] ?>"
                   value="<?= esc($old['word' . $w['num']] ?? '') ?>"
                   maxlength="50"
                   required>
        </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-wc mt-1">
            <i class="bi bi-send-fill me-2"></i>ส่งคำของฉัน
        </button>
    </form>

    <!-- ลิงก์ดู WordCloud และกลับไปหน้าผลงาน -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="<?= base_url('wordcloud/display') ?>" target="_blank" class="text-muted small text-decoration-none">
            <i class="bi bi-tv me-1"></i>ดู WordCloud
        </a>
        <?php if (session()->get('participant_id')): ?>
        <a href="<?= base_url('my-work') ?>" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>กลับไปหน้าผลงาน
        </a>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
