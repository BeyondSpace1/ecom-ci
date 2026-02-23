<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">All Platform Products</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Product Name</th>
                        <th>Vendor (Store)</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                    <tr>
                        <td class="fw-bold text-truncate" style="max-width: 200px;" title="<?= esc($product['name']) ?>"><?= esc($product['name']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= esc($product['store_name']) ?></span></td>
                        <td><?= esc($product['category_name']) ?></td>
                        <td class="fw-semibold">$<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <?php if($product['stock'] > 0): ?>
                                <?= $product['stock'] ?> units
                            <?php else: ?>
                                <span class="text-danger fw-bold">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($products)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No products listed by vendors yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>