<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">System Overview</h2>
    <span class="badge bg-primary fs-6">Logged in as Admin</span>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm h-100 border-0">
            <div class="card-body">
                <h5 class="card-title text-uppercase opacity-75">Total Revenue</h5>
                <p class="card-text display-5 fw-bold">$<?= number_format($total_revenue, 2) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm h-100 border-0">
            <div class="card-body">
                <h5 class="card-title text-uppercase opacity-75">Active Vendors</h5>
                <p class="card-text display-5 fw-bold"><?= $total_vendors ?></p>
                <?php if($pending_vendors > 0): ?>
                    <small class="text-warning fw-bold"><?= $pending_vendors ?> pending approval!</small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm h-100 border-0">
            <div class="card-body">
                <h5 class="card-title text-uppercase opacity-75">Total Orders</h5>
                <p class="card-text display-5 fw-bold"><?= $total_orders ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white shadow-sm h-100 border-0">
            <div class="card-body">
                <h5 class="card-title text-uppercase opacity-75">Total Products</h5>
                <p class="card-text display-5 fw-bold"><?= $total_products ?></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>