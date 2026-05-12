<?php
$participants = $matrix['participants'];
$assignments  = $matrix['assignments'];
$submittedIds = $matrix['submittedIds'];
$totalP = count($participants);
$totalA = count($assignments);
?>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <span class="text-muted small"><i class="bi bi-people me-1"></i>ผู้เข้าอบรม <?= $totalP ?> คน</span>
            <span class="text-muted small"><i class="bi bi-journal me-1"></i>ผลงาน <?= $totalA ?> งาน</span>
            <span class="badge bg-success"><i class="bi bi-check2 me-1"></i>ส่งแล้ว</span>
            <span class="badge bg-secondary"><i class="bi bi-dash me-1"></i>ยังไม่ส่ง</span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-table me-2 text-primary"></i>ตารางการส่งผลงาน</span>
            <input type="text" class="form-control form-control-sm w-auto" id="searchBox" placeholder="ค้นหาชื่อ..." onkeyup="searchTable()">
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" id="submissionTable">
                <thead class="table-primary">
                    <tr>
                        <th style="min-width:180px">ชื่อ-นามสกุล</th>
                        <th style="min-width:80px">รหัส</th>
                        <?php foreach ($assignments as $a): ?>
                        <th class="text-center" style="min-width:100px"><?= esc($a['title']) ?></th>
                        <?php endforeach; ?>
                        <th class="text-center" style="min-width:80px">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $p): ?>
                    <?php
                        $pSubmitted = 0;
                        foreach ($assignments as $a) {
                            if (isset($submittedIds[$p['id']][$a['id']])) $pSubmitted++;
                        }
                    ?>
                    <tr>
                        <td class="fw-medium"><?= esc($p['name']) ?></td>
                        <td><code><?= esc($p['participant_code']) ?></code></td>
                        <?php foreach ($assignments as $a): ?>
                        <td class="text-center">
                            <?php if (isset($submittedIds[$p['id']][$a['id']])): ?>
                                <?php $subId = $submittedIds[$p['id']][$a['id']]; ?>
                                <a href="<?= base_url('admin/submissions/files/' . $subId) ?>" class="btn btn-sm btn-success py-0 px-2">
                                    <i class="bi bi-check2-circle"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted"><i class="bi bi-dash-circle"></i></span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="text-center">
                            <span class="badge bg-<?= $pSubmitted == $totalA ? 'success' : ($pSubmitted > 0 ? 'warning' : 'secondary') ?>">
                                <?= $pSubmitted ?>/<?= $totalA ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($participants)): ?>
                    <tr><td colspan="<?= $totalA + 3 ?>" class="text-center text-muted py-4">ยังไม่มีข้อมูล</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function searchTable() {
    const filter = document.getElementById('searchBox').value.toLowerCase();
    document.querySelectorAll('#submissionTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
}
</script>
