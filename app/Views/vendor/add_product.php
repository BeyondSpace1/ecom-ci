<?= $this->extend('vendor/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="/vendor/products" class="btn btn-outline-secondary btn-sm shadow-sm">← Back</a>
    <h2 class="fw-bold mb-0">Add New Product</h2>
</div>

<div class="card shadow-sm border-0" style="max-width: 800px;">
    <div class="card-body p-4 p-md-5">
        <form action="/vendor/products/add" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control form-control-lg" required>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select form-select-lg" required>
                        <option value="" disabled selected>Select a category...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control form-control-lg" min="0" value="10" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Standard Price ($) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control form-control-lg" step="0.01" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-danger">Offer Price ($) (Optional)</label>
                    <input type="number" name="offer_price" class="form-control form-control-lg" step="0.01" min="0">
                    <div class="form-text">Leave blank for no discount.</div>
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">Product Image</label>
                <input type="file" name="image" class="form-control form-control-lg" accept="image/png, image/jpeg, image/jpg">
                <div class="form-text">Recommended size: 800x800px. Max size: 2MB.</div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Publish Product</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>