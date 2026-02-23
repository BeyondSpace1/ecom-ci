<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="fw-bold">Manage Categories</h2>
    </div>
    <div class="col-md-4 text-end">
        <form action="/admin/categories/add" method="post" class="d-flex gap-2">
            <?= csrf_field() ?>
            <input type="text" name="name" class="form-control" placeholder="New Category Name" required>
            <button type="submit" class="btn btn-primary shadow-sm">Add</button>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td class="fw-bold"><?= esc($cat['name']) ?></td>
                    <td>
                        <?= $cat['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($categories)): ?>
                    <tr><td colspan="3" class="text-center py-4 text-muted">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>