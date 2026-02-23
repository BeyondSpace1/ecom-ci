<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<h2 class="fw-bold mb-4">All Platform Orders</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td class="fw-bold">#ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= esc($order['customer_name']) ?></td>
                    <td class="fw-semibold text-primary">$<?= number_format($order['total_amount'], 2) ?></td>
                    <td>
                        <span class="badge bg-success text-uppercase"><?= esc($order['status']) ?></span>
                    </td>
                    <td class="text-muted"><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($orders)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No orders placed yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>