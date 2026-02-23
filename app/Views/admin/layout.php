<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; width: 250px; }
        .main-content { flex: 1; height: 100vh; overflow-y: auto; }
    </style>
</head>
<body class="d-flex">

    <div class="bg-dark text-white p-3 sidebar shadow">
        <h4 class="fw-bold mb-4 text-center border-bottom pb-2">Admin Panel</h4>
        <ul class="nav flex-column gap-2">
            <li class="nav-item"><a class="nav-link text-white rounded bg-secondary bg-opacity-25" href="/admin/dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/admin/vendors">Manage Vendors</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/admin/categories">Categories</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/admin/users">Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/admin/products">All Products</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/admin/orders">All Orders</a></li>
            <li class="nav-item mt-4"><a class="nav-link text-danger fw-bold" href="/logout">Logout</a></li>
        </ul>
    </div>

    <div class="main-content p-4">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>