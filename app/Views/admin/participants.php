<div class="row g-3 mb-4">
    <!-- Import CSV -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>นำเข้า CSV
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">ไฟล์ CSV ต้องมี 2 คอลัมน์: <strong>ชื่อ, รหัส</strong></p>
                <form action="<?= base_url('admin/participants/import') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="file" class="form-control form-control-sm" name="csv_file" accept=".csv,text/csv" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-upload me-1"></i>นำเข้า
                    </button>
                </form>
                <div class="mt-2">
                    <a href="<?= base_url('admin/participants/export') ?>" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-download me-1"></i>ส่งออก CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Add manually -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-person-plus-fill me-2 text-primary"></i>เพิ่มผู้เข้าอบรม
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/participants/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" name="name" placeholder="ชื่อ-นามสกุล" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" name="participant_code" placeholder="รหัสผู้เข้าอบรม" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-circle me-1"></i>เพิ่ม
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Participants Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-people me-2 text-primary"></i>รายชื่อผู้เข้าอบรม (<?= count($participants) ?> คน)</span>
        <input type="text" class="form-control form-control-sm w-auto" id="searchBox" placeholder="ค้นหา..." onkeyup="searchTable()">
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="participantTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>รหัส</th>
                        <th class="text-center">ส่งงาน</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $i => $p): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td class="fw-medium"><?= esc($p['name']) ?></td>
                        <td><code><?= esc($p['participant_code']) ?></code></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $p['submitted_count'] > 0 ? 'success' : 'secondary' ?>">
                                <?= $p['submitted_count'] ?>/<?= $p['total_assignments'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="editParticipant(<?= $p['id'] ?>, '<?= esc($p['name'], 'js') ?>', '<?= esc($p['participant_code'], 'js') ?>')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="<?= base_url('admin/participants/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('ลบผู้เข้าอบรมคนนี้?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($participants)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีผู้เข้าอบรม</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">แก้ไขผู้เข้าอบรม</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" class="form-control" name="name" id="editName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัส</label>
                        <input type="text" class="form-control" name="participant_code" id="editCode" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editParticipant(id, name, code) {
    document.getElementById('editName').value = name;
    document.getElementById('editCode').value = code;
    document.getElementById('editForm').action = '<?= base_url('admin/participants/edit/') ?>' + id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function searchTable() {
    const filter = document.getElementById('searchBox').value.toLowerCase();
    const rows = document.querySelectorAll('#participantTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
}
</script>
