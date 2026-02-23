<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">Vendor Management</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Store Name</th>
                    <th>Owner Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vendors as $vendor): ?>
                <tr>
                    <td class="fw-bold"><?= esc($vendor['store_name']) ?></td>
                    <td><?= esc($vendor['name']) ?></td>
                    <td><?= esc($vendor['email']) ?></td>
                    <td>
                        <?php if($vendor['approval_status'] === 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php elseif($vendor['approval_status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <?php if($vendor['approval_status'] === 'pending'): ?>
                            <form action="/admin/vendors/approve/<?= $vendor['user_id'] ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-success shadow-sm">Approve</button>
                            </form>
                            <form action="/admin/vendors/reject/<?= $vendor['user_id'] ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger shadow-sm">Reject</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted fst-italic">Reviewed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($vendors)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No vendors registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>