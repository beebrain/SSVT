<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?= esc($assignment['title']) ?> - ส่งผลงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sarabun', sans-serif; }
        body { background: #f5f7fa; }
        .header-bar {
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            color: #fff;
            padding: 16px 20px;
        }
        .upload-zone {
            border: 2px dashed #90caf9;
            border-radius: 16px;
            background: #e3f2fd;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .upload-zone:hover, .upload-zone.dragover {
            border-color: #1565c0;
            background: #bbdefb;
        }
        .upload-zone input[type=file] { display: none; }
        .preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; }
        .preview-item { position: relative; border-radius: 10px; overflow: hidden; aspect-ratio: 1; background: #f5f5f5; }
        .preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .preview-item .remove-btn {
            position: absolute; top: 3px; right: 3px;
            background: rgba(200,0,0,0.8); color: #fff;
            border: none; border-radius: 50%; width: 22px; height: 22px;
            font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .existing-img { border-radius: 10px; overflow: hidden; aspect-ratio: 1; position: relative; }
        .existing-img img { width: 100%; height: 100%; object-fit: cover; }
        .btn-submit-main {
            background: linear-gradient(135deg, #1a237e, #3949ab);
            color: #fff; border: none; border-radius: 14px;
            padding: 15px; font-size: 1.1rem; font-weight: 600; width: 100%;
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="header-bar d-flex align-items-center gap-3">
    <a href="<?= base_url('my-work') ?>" class="text-white text-decoration-none">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <div class="small opacity-75"><?= esc($participant['name']) ?></div>
        <h6 class="mb-0 fw-bold"><?= esc($assignment['title']) ?></h6>
    </div>
</div>

<div class="container py-3 pb-5">
    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-2">
        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2">
        <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Existing files -->
    <?php if (!empty($files)): ?>
    <div class="card border-0 shadow-sm mb-3" style="border-radius:16px;">
        <div class="card-header bg-white fw-semibold border-0 pb-0">
            <i class="bi bi-images me-2 text-success"></i>ไฟล์ที่ส่งแล้ว (<?= count($files) ?> ไฟล์)
        </div>
        <div class="card-body">
            <div class="preview-grid">
                <?php foreach ($files as $f): ?>
                <div class="existing-img">
                    <?php if (str_starts_with($f['mime_type'], 'image/')): ?>
                        <a href="<?= base_url('files/' . $f['stored_name']) ?>" target="_blank">
                            <img src="<?= base_url('files/' . $f['stored_name']) ?>" alt="<?= esc($f['original_name']) ?>">
                        </a>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                            <i class="bi bi-file-earmark text-muted fs-3"></i>
                        </div>
                    <?php endif; ?>
                    <a href="<?= base_url('submit/' . $assignment['id'] . '/delete-file/' . $f['id']) ?>"
                       class="remove-btn"
                       onclick="return confirm('ลบไฟล์นี้?')"
                       style="position:absolute;top:4px;right:4px;">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Upload form -->
    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-header bg-white fw-semibold border-0 pb-0">
            <i class="bi bi-cloud-upload me-2 text-primary"></i>
            <?= $submission ? 'เพิ่มไฟล์เพิ่มเติม' : 'อัปโหลดผลงาน' ?>
        </div>
        <div class="card-body">
            <?php if (!empty($assignment['description'])): ?>
            <div class="alert alert-info py-2 small mb-3">
                <i class="bi bi-info-circle me-1"></i><?= esc($assignment['description']) ?>
            </div>
            <?php endif; ?>

            <form action="<?= base_url('submit/' . $assignment['id']) ?>" method="post" enctype="multipart/form-data" id="uploadForm">
                <?= csrf_field() ?>

                <!-- Upload zone -->
                <div class="upload-zone mb-3" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                    <i class="bi bi-image text-primary" style="font-size:2.5rem;"></i>
                    <div class="fw-semibold mt-2">แตะเพื่อเลือกรูปภาพ</div>
                    <div class="text-muted small">เลือกได้หลายไฟล์พร้อมกัน (กด Ctrl หรือ Shift ขณะเลือกในกล่องโต้ตอบ)</div>
                    <div class="text-muted small">รองรับ JPG, PNG, GIF, WEBP, HEIC — สูงสุด 10MB ต่อไฟล์</div>
                    <input type="file" id="fileInput" name="images[]" multiple accept="image/*" onchange="previewFiles(this)">
                </div>

                <!-- Preview -->
                <div class="preview-grid mb-3" id="previewGrid"></div>

                <!-- Note -->
                <div class="mb-3">
                    <label class="form-label small text-muted">หมายเหตุ (ถ้ามี)</label>
                    <textarea class="form-control" name="note" rows="2" placeholder="หมายเหตุเพิ่มเติม..."><?= esc($submission['note'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-submit-main" id="submitBtn">
                    <i class="bi bi-cloud-upload me-2"></i>
                    <?= $submission ? 'บันทึกการแก้ไข' : 'ส่งผลงาน' ?>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Drag & drop
const zone = document.getElementById('uploadZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragover');
    const input = document.getElementById('fileInput');
    const dt = new DataTransfer();
    [...input.files, ...e.dataTransfer.files].forEach(f => {
        if (f.type.startsWith('image/')) dt.items.add(f);
    });
    input.files = dt.files;
    previewFiles(input);
});

function removeFileFromInput(index) {
    const input = document.getElementById('fileInput');
    const dt = new DataTransfer();
    [...input.files].forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });
    input.files = dt.files;
    previewFiles(input);
}

function previewFiles(input) {
    const grid = document.getElementById('previewGrid');
    grid.innerHTML = '';
    [...input.files].forEach((file, i) => {
        const div = document.createElement('div');
        div.className = 'preview-item';
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            div.appendChild(img);
        } else {
            div.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-file-earmark fs-3 text-muted"></i></div>';
        }
        const btn = document.createElement('button');
        btn.className = 'remove-btn';
        btn.innerHTML = '<i class="bi bi-x"></i>';
        btn.type = 'button';
        btn.onclick = () => removeFileFromInput(i);
        div.appendChild(btn);
        grid.appendChild(div);
    });
}

// Submit with loading state
document.getElementById('uploadForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังอัปโหลด...';
});
</script>
</body>
</html>
