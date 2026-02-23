<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">DevShop Checkout</a>
            <a href="/cart" class="btn btn-outline-light btn-sm">← Back to Cart</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="fw-bold mb-4">Payment Details</h3>
                        
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>

                        <form action="/checkout/process" method="post" id="checkout-form">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Full Name</label>
                                <input type="text" class="form-control form-control-lg" value="<?= session()->get('name') ?>" readonly>
                                <div class="form-text">Billed to your registered account name.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Select Payment Method</label>
                                <select name="payment_method" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>Choose a simulated method...</option>
                                    <option value="credit_card">Credit/Debit Card (Simulated)</option>
                                    <option value="paypal">PayPal (Simulated)</option>
                                    <option value="wallet">Digital Wallet (Simulated)</option>
                                </select>
                            </div>

                            <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info-emphasis d-flex align-items-center mb-5">
                                <span class="fs-4 me-3">ℹ️</span>
                                <div>
                                    <strong>Multi-Vendor Order Note:</strong> Your items are sourced from different vendors. Our system will automatically split this order and distribute earnings seamlessly behind the scenes.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" id="pay-btn">
                                Confirm & Pay $<?= number_format($total, 2) ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 border-bottom pb-2">Order Summary</h4>
                        
                        <ul class="list-group list-group-flush mb-4 bg-transparent">
                            <?php foreach($cart as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                    <div>
                                        <h6 class="my-0 fw-bold"><?= esc($item['name']) ?></h6>
                                        <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                                    </div>
                                    <span class="text-muted fw-semibold">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Taxes & Shipping</span>
                            <span class="text-success fw-semibold">Free (Promo)</span>
                        </div>
                        
                        <div class="d-flex justify-content-between fs-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary">$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent duplicate payments by disabling the button on submit
        document.getElementById('checkout-form').addEventListener('submit', function() {
            const btn = document.getElementById('pay-btn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing Transaction...';
            btn.classList.add('disabled');
        });
    </script>
</body>
</html>