<div class="mb-3">
    <a href="<?= base_url('admin/submissions') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>กลับ
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-info-circle me-2 text-primary"></i>ข้อมูลการส่งผลงาน
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-sm-6">
                <span class="text-muted">ผู้ส่ง:</span> <strong><?= esc($submission['participant_name']) ?></strong>
            </div>
            <div class="col-sm-6">
                <span class="text-muted">ผลงาน:</span> <strong><?= esc($submission['assignment_title']) ?></strong>
            </div>
            <div class="col-sm-6">
                <span class="text-muted">วันที่ส่ง:</span> <?= $submission['submitted_at'] ?>
            </div>
            <div class="col-sm-6">
                <span class="text-muted">อัปเดตล่าสุด:</span> <?= $submission['updated_at'] ?>
            </div>
            <?php if (!empty($submission['note'])): ?>
            <div class="col-12">
                <span class="text-muted">หมายเหตุ:</span> <?= esc($submission['note']) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-images me-2 text-primary"></i>ไฟล์ที่ส่ง (<?= count($submission['files']) ?> ไฟล์)
    </div>
    <div class="card-body">
        <?php if (empty($submission['files'])): ?>
            <p class="text-muted text-center py-3">ยังไม่มีไฟล์</p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($submission['files'] as $f): ?>
                <div class="col-6 col-md-3">
                    <div class="card border h-100">
                        <?php if (str_starts_with($f['mime_type'], 'image/')): ?>
                            <a href="<?= base_url('files/' . $f['stored_name']) ?>" target="_blank">
                                <img src="<?= base_url('files/' . $f['stored_name']) ?>" class="card-img-top" style="height:140px;object-fit:cover;" alt="<?= esc($f['original_name']) ?>">
                            </a>
                        <?php else: ?>
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:140px;">
                                <i class="bi bi-file-earmark display-4 text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body p-2">
                            <p class="small mb-1 text-truncate" title="<?= esc($f['original_name']) ?>"><?= esc($f['original_name']) ?></p>
                            <p class="text-muted" style="font-size:0.7rem;"><?= round($f['file_size']/1024, 1) ?> KB</p>
                            <div class="d-flex gap-1">
                                <a href="<?= base_url('files/' . $f['stored_name']) ?>" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1 py-0">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= base_url('admin/submissions/delete-file/' . $f['id']) ?>" class="btn btn-sm btn-outline-danger py-0" onclick="return confirm('ลบไฟล์นี้?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
