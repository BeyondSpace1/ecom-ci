<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; width: 250px; background-color: #2c3e50; }
        .main-content { flex: 1; height: 100vh; overflow-y: auto; }
        .nav-link { color: #ecf0f1; margin-bottom: 5px; border-radius: 5px; transition: background 0.2s; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); color: white; }
    </style>
</head>
<body class="d-flex">

    <div class="sidebar p-3 shadow">
        <h4 class="fw-bold mb-4 text-center text-white border-bottom pb-3">Vendor Panel</h4>
        <ul class="nav flex-column gap-1">
            <li class="nav-item"><a class="nav-link" href="/vendor/dashboard">📊 Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/vendor/profile">🏪 Store Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="/vendor/products">📦 My Products</a></li>
            <li class="nav-item"><a class="nav-link" href="/vendor/orders">🛍️ My Orders</a></li>
            <li class="nav-item mt-5"><a class="nav-link text-danger fw-bold" href="/logout">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main-content p-4 p-md-5">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>