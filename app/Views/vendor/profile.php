<?= $this->extend('vendor/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">Store Profile</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="/vendor/profile/update" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Store Name</label>
                        <input type="text" name="store_name" class="form-control form-control-lg" value="<?= esc($vendor['store_name']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Store Logo</label>
                        <?php if(!empty($vendor['store_logo'])): ?>
                            <div class="mb-3">
                                <img src="/uploads/logos/<?= esc($vendor['store_logo']) ?>" alt="Current Logo" class="img-thumbnail" style="height: 100px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="store_logo" class="form-control form-control-lg" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text">Leave blank to keep current logo. Max size: 2MB.</div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Save Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>