<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-8"><h2 class="fw-bold">Manage Categories</h2></div>
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
                <tr><th>ID</th><th>Category Name & Status</th><th>Current State</th></tr>
            </thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td>
                        <form action="/admin/categories/update/<?= $cat['id'] ?>" method="post" class="d-flex gap-2 align-items-center">
                            <?= csrf_field() ?>
                            <input type="text" name="name" class="form-control form-control-sm" value="<?= esc($cat['name']) ?>" required>
                            <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                <option value="1" <?= $cat['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $cat['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                    <td><?= $cat['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>