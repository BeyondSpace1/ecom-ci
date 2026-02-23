<?= $this->extend('vendor/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Store Overview</h2>
    <span class="badge bg-primary fs-6">Vendor Mode</span>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm h-100 border-0">
            <div class="card-body p-4">
                <h6 class="card-title text-uppercase fw-bold opacity-75 mb-3">Total Earnings</h6>
                <p class="card-text display-5 fw-bold">$<?= number_format($total_earnings, 2) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm h-100 border-0">
            <div class="card-body p-4">
                <h6 class="card-title text-uppercase fw-bold opacity-75 mb-3">Items Sold</h6>
                <p class="card-text display-5 fw-bold"><?= $items_sold ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-secondary text-white shadow-sm h-100 border-0">
            <div class="card-body p-4">
                <h6 class="card-title text-uppercase fw-bold opacity-75 mb-3">Active Products</h6>
                <p class="card-text display-5 fw-bold"><?= $total_products ?></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>