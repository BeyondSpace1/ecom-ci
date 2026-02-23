<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .success-icon { font-size: 5rem; color: #198754; animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; transform: scale(0); }
        @keyframes popIn { to { transform: scale(1); } }
    </style>
</head>
<body>

    <div class="container text-center">
        <div class="card shadow-lg border-0 rounded-4 p-5 mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <div class="success-icon mb-3">✓</div>
                <h2 class="fw-bold mb-3">Payment Successful!</h2>
                <p class="text-muted mb-4 fs-5">Your multi-vendor order has been processed securely. The vendors have been notified to prepare your items.</p>
                
                <div class="d-grid gap-2">
                    <a href="/" class="btn btn-primary btn-lg fw-bold shadow-sm">Return to Shop</a>
                    <a href="/logout" class="btn btn-outline-secondary">Log Out</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>