<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-cloud me-2 text-info"></i>WordCloud (<?= $total ?> ผู้เข้าร่วม)</span>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('wordcloud/display') ?>" target="_blank" class="btn btn-info btn-sm">
                        <i class="bi bi-tv me-1"></i>แสดงแบบเต็มจอ
                    </a>
                    <a href="<?= base_url('admin/wordcloud/clear') ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('ล้างข้อมูล WordCloud ทั้งหมด?')">
                        <i class="bi bi-trash me-1"></i>ล้างข้อมูล
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($words)): ?>
                    <p class="text-muted text-center py-5">ยังไม่มีข้อมูล WordCloud</p>
                <?php else: ?>
                    <div id="wordcloudCanvas" style="width:100%;height:450px;background:#fafafa;border-radius:12px;"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($words)): ?>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-bar-chart me-2 text-primary"></i>ความถี่คำ (Top 20)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>คำ</th><th>ความถี่</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($words, 0, 20) as $i => $w): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td class="fw-medium"><?= esc($w['text']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:10px;">
                                            <div class="progress-bar bg-info" style="width:<?= min(100, $w['size'] / max(array_column($words, 'size')) * 100) ?>%"></div>
                                        </div>
                                        <span class="badge bg-info"><?= $w['size'] ?></span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($words)): ?>
<script src="https://cdn.jsdelivr.net/npm/wordcloud@1.2.2/src/wordcloud2.js"></script>
<script>
const wordsData = <?= json_encode(array_map(fn($w) => [$w['text'], $w['size'] * 10 + 20], $words)) ?>;
window.addEventListener('load', function() {
    const el = document.getElementById('wordcloudCanvas');
    WordCloud(el, {
        list: wordsData,
        gridSize: 12,
        weightFactor: 3,
        fontFamily: 'Sarabun, sans-serif',
        color: function() {
            const colors = ['#1565C0','#2E7D32','#F57F17','#6A1B9A','#00838F','#C62828','#00695C'];
            return colors[Math.floor(Math.random() * colors.length)];
        },
        rotateRatio: 0.3,
        backgroundColor: '#fafafa',
    });
});
</script>
<?php endif; ?>
