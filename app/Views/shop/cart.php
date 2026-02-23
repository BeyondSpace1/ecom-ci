<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .cart-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
        .qty-input { width: 70px; text-align: center; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">DevShop</a>
            <a href="/" class="btn btn-outline-light btn-sm">← Continue Shopping</a>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="fw-bold mb-4">Shopping Cart</h2>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if(empty($cart)): ?>
            <div class="text-center py-5 bg-white rounded shadow-sm border-0">
                <h4 class="text-muted">Your cart is currently empty.</h4>
                <a href="/" class="btn btn-primary mt-3">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($cart as $item): ?>
                                            <tr id="row-<?= $item['product_id'] ?>">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <?php $imgSrc = !empty($item['image']) ? '/uploads/products/' . esc($item['image']) : 'https://placehold.co/80?text=Img'; ?>
                                                        <img src="<?= $imgSrc ?>" class="cart-img shadow-sm" alt="<?= esc($item['name']) ?>">
                                                        <span class="fw-bold text-dark"><?= esc($item['name']) ?></span>
                                                    </div>
                                                </td>
                                                <td class="fw-semibold text-muted">$<?= number_format($item['price'], 2) ?></td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control qty-input shadow-sm" 
                                                           data-product-id="<?= $item['product_id'] ?>" 
                                                           value="<?= $item['quantity'] ?>" 
                                                           min="1" 
                                                           max="<?= $item['max_stock'] ?>">
                                                </td>
                                                <td class="fw-bold text-dark fs-5 item-total" id="item-total-<?= $item['product_id'] ?>">
                                                    $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <a href="/cart/remove/<?= $item['product_id'] ?>" class="btn btn-sm btn-outline-danger shadow-sm">Remove</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4 border-bottom pb-2">Order Summary</h4>
                            
                            <div class="d-flex justify-content-between mb-3 fs-5">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold text-dark" id="cart-grand-total">$<?= number_format($total, 2) ?></span>
                            </div>
                            
                            <hr>
                            
                            <a href="/checkout" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">Proceed to Checkout</a>
                            
                            <div class="mt-3 text-center text-muted small">
                                🔒 Secure connection. Multi-vendor order splitting enabled.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qtyInputs = document.querySelectorAll('.qty-input');
            const csrfName = document.getElementById('csrf_token').getAttribute('name');
            
            qtyInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const productId = this.getAttribute('data-product-id');
                    let newQty = parseInt(this.value);
                    const maxStock = parseInt(this.getAttribute('max'));
                    const csrfHash = document.getElementById('csrf_token').value;

                    // Basic frontend validation
                    if (newQty < 1) { newQty = 1; this.value = 1; }
                    if (newQty > maxStock) { 
                        alert('Cannot exceed available stock limit of ' + maxStock); 
                        newQty = maxStock; 
                        this.value = maxStock; 
                    }

                    // Prepare form data for POST request
                    const formData = new FormData();
                    formData.append(csrfName, csrfHash);
                    formData.append('product_id', productId);
                    formData.append('quantity', newQty);

                    // Send AJAX request
                    fetch('/cart/update', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update the specific row's total price
                            document.getElementById('item-total-' + productId).innerText = '$' + data.new_item_total;
                            // Update the cart grand total
                            document.getElementById('cart-grand-total').innerText = '$' + data.new_cart_total;
                        } else {
                            alert(data.message || 'Error updating cart');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
</body>
</html>