<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ShopController::index');
// Authentication Routes
$routes->get('/login', 'Auth::login');
$routes->post('/loginAuth', 'Auth::loginAuth');
$routes->get('/register', 'Auth::register');
$routes->post('/registerStore', 'Auth::registerStore');
$routes->get('/logout', 'Auth::logout');
// Admin Protected Routes
$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    
    // Vendor Management
    $routes->get('vendors', 'AdminController::vendors');
    $routes->post('vendors/approve/(:num)', 'AdminController::approveVendor/$1');
    $routes->post('vendors/reject/(:num)', 'AdminController::rejectVendor/$1');
    
    // User Management
    $routes->get('users', 'AdminController::users');
    $routes->post('users/toggle-status/(:num)', 'AdminController::toggleUserStatus/$1');
    
    // Categories
    $routes->get('categories', 'AdminController::categories');
    $routes->post('categories/add', 'AdminController::addCategory');
    
    // View-Only Routes (Products, Orders)
    $routes->get('products', 'AdminController::products');
    $routes->get('orders', 'AdminController::orders');

    $routes->post('admin/categories/update/(:num)', 'AdminController::updateCategory/$1');
    $routes->post('admin/products/toggle-status/(:num)', 'AdminController::toggleProductStatus/$1');
});
// Vendor Protected Routes
$routes->group('vendor', ['filter' => 'role:vendor'], static function ($routes) {
    $routes->get('dashboard', 'VendorController::dashboard');
    
    // Profile & Logo
    $routes->get('profile', 'VendorController::profile');
    $routes->post('profile/update', 'VendorController::updateProfile');
    
    // Product Management
    $routes->get('products', 'VendorController::products');
    $routes->match(['get', 'post'], 'products/add', 'VendorController::addProduct');
    $routes->match(['get', 'post'], 'products/edit/(:num)', 'VendorController::editProduct/$1');
    $routes->get('products/delete/(:num)', 'VendorController::deleteProduct/$1');
    
    // AJAX Product Search Endpoint
    $routes->get('products/search', 'VendorController::searchProductsAjax');
    
    // Orders
    $routes->get('orders', 'VendorController::orders');

    $routes->post('vendor/products/toggle-status/(:num)', 'VendorController::toggleProductStatus/$1');
    $routes->post('vendor/orders/update-status/(:num)', 'VendorController::updateOrderStatus/$1');
});
// Public Shop & AJAX Routes
$routes->get('/', 'ShopController::index');
$routes->get('/shop/filter', 'ShopController::filterProducts'); // AJAX Endpoint

// Cart Management (Publicly accessible until checkout)
$routes->get('/cart', 'ShopController::cart');
$routes->post('/cart/add', 'ShopController::addToCart');
$routes->post('/cart/update', 'ShopController::updateCart'); // AJAX Endpoint
$routes->get('/cart/remove/(:any)', 'ShopController::removeFromCart/$1');

// Checkout Process (Protected: Only logged-in Customers can buy)
$routes->group('checkout', ['filter' => 'role:customer'], static function ($routes) {
    $routes->get('/', 'ShopController::checkoutView');
    $routes->post('process', 'Checkout::processCheckout'); // We wrote this in step 1!
    $routes->get('success', 'ShopController::success');
});