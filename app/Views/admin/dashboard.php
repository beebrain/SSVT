<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="display-6 text-primary mb-1"><i class="bi bi-people-fill"></i></div>
                <div class="h2 fw-bold mb-0"><?= $totalParticipants ?></div>
                <div class="text-muted small">ผู้เข้าอบรม</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="display-6 text-success mb-1"><i class="bi bi-journal-check"></i></div>
                <div class="h2 fw-bold mb-0"><?= $totalAssignments ?></div>
                <div class="text-muted small">ผลงานทั้งหมด</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="display-6 text-warning mb-1"><i class="bi bi-upload"></i></div>
                <div class="h2 fw-bold mb-0"><?= $totalSubmissions ?></div>
                <div class="text-muted small">การส่งงาน</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="display-6 text-info mb-1"><i class="bi bi-cloud-fill"></i></div>
                <div class="h2 fw-bold mb-0"><?= $totalWordcloud ?></div>
                <div class="text-muted small">WordCloud</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>ความคืบหน้าการส่งผลงาน
    </div>
    <div class="card-body">
        <?php if (empty($assignments)): ?>
            <p class="text-muted">ยังไม่มีผลงาน</p>
        <?php else: ?>
            <?php foreach ($assignments as $a): ?>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-medium"><?= esc($a['title']) ?></span>
                    <span class="text-muted small"><?= $a['submitted'] ?> / <?= $totalP ?> คน (<?= $a['percent'] ?>%)</span>
                </div>
                <div class="progress" style="height: 12px; border-radius: 6px;">
                    <div class="progress-bar bg-success" style="width: <?= $a['percent'] ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-link-45deg me-2 text-primary"></i>ลิงก์สำหรับผู้เข้าอบรม</h6>
                <p class="text-muted small">แชร์ลิงก์นี้ให้ผู้เข้าอบรมเพื่อเข้าส่งผลงาน</p>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="loginLink" value="<?= base_url('login') ?>" readonly>
                    <button class="btn btn-outline-primary btn-sm" onclick="copyLink()">
                        <i class="bi bi-clipboard"></i> คัดลอก
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-cloud me-2 text-info"></i>ลิงก์ WordCloud</h6>
                <p class="text-muted small">ลิงก์สำหรับผู้เข้าร่วมพิมพ์คำ WordCloud</p>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="wcLink" value="<?= base_url('wordcloud') ?>" readonly>
                    <button class="btn btn-outline-info btn-sm" onclick="copyWcLink()">
                        <i class="bi bi-clipboard"></i> คัดลอก
                    </button>
                </div>
                <a href="<?= base_url('wordcloud/display') ?>" target="_blank" class="btn btn-info btn-sm mt-2 w-100">
                    <i class="bi bi-tv me-1"></i>แสดง WordCloud บนจอ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(document.getElementById('loginLink').value);
    alert('คัดลอกลิงก์แล้ว!');
}
function copyWcLink() {
    navigator.clipboard.writeText(document.getElementById('wcLink').value);
    alert('คัดลอกลิงก์แล้ว!');
}
</script>
