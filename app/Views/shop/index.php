<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Vendor E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .product-card { transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
        .product-img { height: 200px; object-fit: cover; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">DevShop</a>
            <div class="d-flex align-items-center gap-3">
                <a href="/cart" class="btn btn-outline-light position-relative">
                    🛒 Cart
                    <?php if(session()->has('cart') && count(session()->get('cart')) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= count(session()->get('cart')) ?>
                        </span>
                    <?php endif; ?>
                </a>
                <?php if(session()->get('isLoggedIn')): ?>
                    <a href="/login" class="btn btn-primary">Dashboard</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-0">Latest Products</h2>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="btn-group shadow-sm" role="group" id="category-filters">
                    <button type="button" class="btn btn-dark filter-btn active" data-id="all">All</button>
                    <?php foreach($categories as $cat): ?>
                        <button type="button" class="btn btn-outline-dark filter-btn" data-id="<?= $cat['id'] ?>">
                            <?= esc($cat['name']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row g-4" id="product-grid">
            <?php foreach($products as $product): ?>
                <div class="col-md-4 col-lg-3 product-item">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <?php $imgSrc = !empty($product['image']) ? '/uploads/products/' . esc($product['image']) : 'https://placehold.co/400x300?text=No+Image'; ?>
                        <img src="<?= $imgSrc ?>" class="card-img-top product-img" alt="<?= esc($product['name']) ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate" title="<?= esc($product['name']) ?>"><?= esc($product['name']) ?></h5>
                            
                            <div class="mb-3">
                                <?php if(!empty($product['offer_price']) && $product['offer_price'] > 0): ?>
                                    <span class="text-danger fw-bold fs-5">$<?= number_format($product['offer_price'], 2) ?></span>
                                    <span class="text-muted text-decoration-line-through ms-2">$<?= number_format($product['price'], 2) ?></span>
                                <?php else: ?>
                                    <span class="fw-bold fs-5">$<?= number_format($product['price'], 2) ?></span>
                                <?php endif; ?>
                            </div>

                            <form action="/cart/add" method="post" class="mt-auto">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="vendor_id" value="<?= $product['vendor_id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if(empty($products)): ?>
                <div class="col-12 text-center py-5 text-muted">
                    <h4>No products available right now.</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const productGrid = document.getElementById('product-grid');
            
            // Store CSRF tokens for dynamically generated forms
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active state
                    filterBtns.forEach(b => { b.classList.remove('btn-dark'); b.classList.add('btn-outline-dark'); });
                    this.classList.remove('btn-outline-dark');
                    this.classList.add('btn-dark');

                    const categoryId = this.getAttribute('data-id');

                    // Fetch AJAX Data
                    fetch(`/shop/filter?category_id=${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear existing grid
                            productGrid.innerHTML = '';

                            if (data.length === 0) {
                                productGrid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><h4>No products found in this category.</h4></div>';
                                return;
                            }

                            // Rebuild grid with new data
                            data.forEach(product => {
                                const imgSrc = product.image ? `/uploads/products/${product.image}` : 'https://placehold.co/400x300?text=No+Image';
                                
                                // Handle pricing display
                                let priceHtml = '';
                                if (product.offer_price && product.offer_price > 0) {
                                    priceHtml = `<span class="text-danger fw-bold fs-5">$${parseFloat(product.offer_price).toFixed(2)}</span>
                                                 <span class="text-muted text-decoration-line-through ms-2">$${parseFloat(product.price).toFixed(2)}</span>`;
                                } else {
                                    priceHtml = `<span class="fw-bold fs-5">$${parseFloat(product.price).toFixed(2)}</span>`;
                                }

                                const cardHtml = `
                                    <div class="col-md-4 col-lg-3 product-item">
                                        <div class="card h-100 border-0 shadow-sm product-card">
                                            <img src="${imgSrc}" class="card-img-top product-img" alt="${product.name}">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title fw-bold text-truncate" title="${product.name}">${product.name}</h5>
                                                <div class="mb-3">${priceHtml}</div>
                                                
                                                <form action="/cart/add" method="post" class="mt-auto">
                                                    <input type="hidden" name="${csrfName}" value="${csrfHash}">
                                                    <input type="hidden" name="product_id" value="${product.id}">
                                                    <input type="hidden" name="vendor_id" value="${product.vendor_id}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Add to Cart</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                productGrid.insertAdjacentHTML('beforeend', cardHtml);
                            });
                        })
                        .catch(error => console.error('Error fetching products:', error));
                });
            });
        });
    </script>
</body>
</html>