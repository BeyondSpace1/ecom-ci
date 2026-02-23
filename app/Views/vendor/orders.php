<?= $this->extend('vendor/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">My Orders</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Main Order ID</th>
                        <th>Product Sold</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>My Revenue</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($order_items as $item): ?>
                    <tr>
                        <td class="ps-4 fw-bold">#ORD-<?= str_pad($item['order_id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td class="fw-bold text-primary"><?= esc($item['product_name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="text-muted">$<?= number_format($item['price'], 2) ?></td>
                        <td class="fw-bold text-success">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        <td class="text-muted"><?= date('M d, Y H:i', strtotime($item['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($order_items)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">You haven't received any orders yet. Add some products!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>