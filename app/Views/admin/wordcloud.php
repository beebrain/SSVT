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
                    <div id="wordcloudCanvas" style="width:100%;height:450px;background:#ffffff;border:1px solid rgba(0,0,0,0.06);border-radius:12px;"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($words)): ?>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-bar-chart me-2 text-primary"></i>ความถี่ของคำ (Top 20)
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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/wordcloud@1.2.2/src/wordcloud2.js"></script>
<script>
const wordsData = <?= json_encode($words) ?>;
const wcColors = [
    '#4a6b52', '#5c7d64', '#6b8e7a', '#3d5c44',
    '#4a6d8c', '#5a7a9e', '#5c6b8a', '#6b5b8c',
    '#a85c4a', '#9a6b4a', '#8b7355', '#7d6b55',
    '#5a5a58', '#6d6d6a', '#4a5568',
];
window.addEventListener('load', function() {
    const el = document.getElementById('wordcloudCanvas');
    const maxSize = Math.max(...wordsData.map(d => d.size));
    const minSize = Math.min(...wordsData.map(d => d.size));
    const list = wordsData.map(w => {
        const normalized = (w.size - minSize) / (maxSize - minSize + 1);
        const fontSize = Math.round(14 + normalized * 48);
        return [w.text, fontSize];
    });
    WordCloud(el, {
        list: list,
        gridSize: 8,
        weightFactor: 1,
        fontFamily: 'Inter, Sarabun, system-ui, sans-serif',
        fontWeight: (word, weight, fontSize) => (fontSize >= 40 ? '600' : '500'),
        color: () => wcColors[Math.floor(Math.random() * wcColors.length)],
        rotateRatio: 0,
        minRotation: 0,
        maxRotation: 0,
        ellipticity: 0.82,
        backgroundColor: '#ffffff',
    });
});
</script>
<?php endif; ?>
