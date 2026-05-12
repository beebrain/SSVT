<!-- Add Assignment -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่มผลงาน
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/assignments/save') ?>" method="post" class="row g-2">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <input type="text" class="form-control" name="title" placeholder="ชื่อผลงาน เช่น ผลงานที่ 1" required>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="description" placeholder="คำอธิบาย">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="assignment_order" placeholder="ลำดับ" value="1" min="1">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Assignments list -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-journal-text me-2 text-primary"></i>รายการผลงาน
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อผลงาน</th>
                        <th>คำอธิบาย</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $a): ?>
                    <tr>
                        <td><?= $a['assignment_order'] ?></td>
                        <td class="fw-medium"><?= esc($a['title']) ?></td>
                        <td class="text-muted"><?= esc($a['description']) ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $a['is_active'] ? 'success' : 'secondary' ?>">
                                <?= $a['is_active'] ? 'เปิดใช้' : 'ปิด' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="editAssignment(<?= $a['id'] ?>, '<?= esc($a['title'], 'js') ?>', '<?= esc($a['description'], 'js') ?>', <?= $a['assignment_order'] ?>, <?= $a['is_active'] ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="<?= base_url('admin/assignments/delete/' . $a['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('ลบผลงานนี้?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($assignments)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีผลงาน</td></tr>
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
                <h5 class="modal-title">แก้ไขผลงาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ชื่อผลงาน</label>
                        <input type="text" class="form-control" name="title" id="editTitle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">คำอธิบาย</label>
                        <input type="text" class="form-control" name="description" id="editDesc">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ลำดับ</label>
                        <input type="number" class="form-control" name="assignment_order" id="editOrder" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">สถานะ</label>
                        <select class="form-select" name="is_active" id="editActive">
                            <option value="1">เปิดใช้</option>
                            <option value="0">ปิด</option>
                        </select>
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
function editAssignment(id, title, desc, order, active) {
    document.getElementById('editTitle').value = title;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editOrder').value = order;
    document.getElementById('editActive').value = active;
    document.getElementById('editForm').action = '<?= base_url('admin/assignments/edit/') ?>' + id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
