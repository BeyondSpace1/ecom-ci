<?= $this->extend('vendor/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">My Products</h2>
    <a href="/vendor/products/add" class="btn btn-primary fw-bold shadow-sm">+ Add New Product</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                    <tr>
                        <td class="ps-4">
                            <?php $imgSrc = !empty($product['image']) ? '/uploads/products/' . esc($product['image']) : 'https://placehold.co/50?text=No+Img'; ?>
                            <img src="<?= $imgSrc ?>" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td class="fw-bold"><?= esc($product['name']) ?></td>
                        <td><?= esc($product['category_name']) ?></td>
                        <td>
                            <?php if(!empty($product['offer_price']) && $product['offer_price'] > 0): ?>
                                <span class="text-danger fw-bold">$<?= number_format($product['offer_price'], 2) ?></span>
                                <small class="text-muted text-decoration-line-through ms-1">$<?= number_format($product['price'], 2) ?></small>
                            <?php else: ?>
                                <span class="fw-bold">$<?= number_format($product['price'], 2) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($product['stock'] > 0): ?>
                                <span class="badge bg-success"><?= $product['stock'] ?> in stock</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="/vendor/products/delete/<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($products)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">You haven't added any products yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>