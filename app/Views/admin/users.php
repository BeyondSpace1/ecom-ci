<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">System Users (Customers & Vendors)</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td class="fw-bold"><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><span class="badge bg-secondary text-uppercase"><?= esc($user['role']) ?></span></td>
                    <td>
                        <?= $user['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deactivated</span>' ?>
                    </td>
                    <td class="text-end">
                        <form action="/admin/users/toggle-status/<?= $user['id'] ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm <?= $user['status'] == 1 ? 'btn-outline-danger' : 'btn-outline-success' ?> shadow-sm">
                                <?= $user['status'] == 1 ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>