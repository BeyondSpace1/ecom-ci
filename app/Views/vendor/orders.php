<?= $this->extend('vendor/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">My Orders</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Product Sold</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Revenue</th>
                        <th>Order Date</th>
                        <th class="pe-4 text-end">Update Status</th>
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
                        <td class="pe-4 text-end">
                            <form action="/vendor/orders/update-status/<?= $item['id'] ?>" method="post" class="d-flex gap-2 justify-content-end">
                                <?= csrf_field() ?>
                                <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                    <option value="pending" <?= ($item['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= ($item['status'] ?? '') == 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="shipped" <?= ($item['status'] ?? '') == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= ($item['status'] ?? '') == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary shadow-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($order_items)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">You haven't received any orders yet. Add some products!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>