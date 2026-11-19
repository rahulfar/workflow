<?php
$pageTitle = "File Manager";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();

$files = $db->query("
    SELECT tf.*, u.name as uploader_name
    FROM task_files tf
    JOIN users u ON tf.user_id = u.id
    ORDER BY tf.id DESC
")->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-folder-open text-warning me-2"></i>File Manager</h3>
                <p class="text-secondary small mb-0">Repository for task attachments, project assets, and work documents.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFileModal"><i class="fas fa-upload me-1"></i>Upload Document</button>
        </div>

        <div class="row g-4">
            <?php if ($files): foreach ($files as $f): 
                $canDelete = (hasRole('admin') || ($user['role_name'] ?? '') === 'admin' || (int)$f['user_id'] === (int)$user['id']);
            ?>
                <div class="col-xl-3 col-lg-4 col-md-6" id="file-card-<?= (int)$f['id'] ?>">
                    <div class="glass-card text-center p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <i class="fas fa-file-lines display-4 text-primary mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?= sanitize($f['file_name']) ?>"><?= sanitize($f['file_name']) ?></h6>
                            <div class="small text-muted mb-3"><?= round($f['file_size'] / 1024, 1) ?> KB • Uploaded by <?= sanitize($f['uploader_name']) ?></div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= BASE_URL ?>/api/files?action=download&id=<?= (int)$f['id'] ?>" class="btn btn-sm btn-outline-primary flex-fill"><i class="fas fa-download me-1"></i>Download</a>
                            <?php if ($canDelete): ?>
                                <button type="button" onclick="deleteFile(<?= (int)$f['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Delete File"><i class="fas fa-trash"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-12"><div class="glass-card text-center text-muted py-5"><i class="fas fa-folder-open fs-1 mb-2"></i><div>No uploaded files yet.</div></div></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Select File *</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="uploadSubmitBtn" class="btn btn-primary fw-bold">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('uploadSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';

        const formData = new FormData(this);
        formData.append('action', 'upload');
        formData.append('csrf_token', CSRF_TOKEN);

        try {
            const res = await fetch('<?= BASE_URL ?>/api/files', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Uploaded',
                    text: data.message || 'File uploaded successfully!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Failed to upload file.', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Upload';
            }
        } catch (err) {
            Swal.fire('Error', 'Network error uploading file.', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Upload';
        }
    });

    async function deleteFile(id) {
        const confirm = await Swal.fire({
            title: 'Delete this document?',
            text: 'This action permanently removes the file.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete file'
        });

        if (!confirm.isConfirmed) return;

        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        formData.append('csrf_token', CSRF_TOKEN);

        try {
            const res = await fetch('<?= BASE_URL ?>/api/files', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                const el = document.getElementById('file-card-' + id);
                if (el) el.remove();
                Swal.fire('Deleted', data.message || 'File removed.', 'success');
            } else {
                Swal.fire('Error', data.message || 'Could not delete file.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Network error deleting file.', 'error');
        }
    }
</script>
