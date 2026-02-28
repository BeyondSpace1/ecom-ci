<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class ShopController extends BaseController
{
    // --- CATALOG & AJAX FILTERING ---
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $data['categories'] = $categoryModel->where('status', 1)->findAll();
        
        // JOIN vendors to verify approval_status
        $data['products'] = $productModel->select('products.*, vendors.store_name')
            ->join('vendors', 'vendors.user_id = products.vendor_id')
            ->where('vendors.approval_status', 'approved') // Only show approved vendors' products
            ->where('products.status', 1) // Only show active products
            ->where('products.stock >', 0) // Only show in-stock products
            ->findAll();

        return view('shop/index', $data);
    }

    public function filterProducts()
    {
        $categoryId = $this->request->getGet('category_id');
        $productModel = new ProductModel();

        $query = $productModel->where('status', 1)->where('stock >', 0);
        
        // If a specific category is selected (not "All")
        if (!empty($categoryId) && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $products = $query->findAll();
        
        // Return strictly formatted JSON for the AJAX frontend
        return $this->response->setJSON($products);
    }

    // --- SESSION CART MANAGEMENT ---
    public function cart()
    {
        $session = session();
        $data['cart'] = $session->get('cart') ?? [];
        
        // Auto Total Calculation
        $data['total'] = array_reduce($data['cart'], function($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return view('shop/cart', $data);
    }

    public function addToCart()
    {
        $session = session();
        $cart = $session->get('cart') ?? [];
        
        // Strict type casting to prevent payload manipulation
        $productId = (int) $this->request->getPost('product_id');
        $vendorId  = (int) $this->request->getPost('vendor_id');
        $quantity  = (int) $this->request->getPost('quantity') ?: 1;
        
        $productModel = new ProductModel();
        $product = $productModel->find($productId);

        if (!$product || $product['stock'] < $quantity) {
            return redirect()->back()->with('error', 'Product unavailable or insufficient stock.');
        }

        // Determine price (use offer_price if it exists and is greater than 0)
        $price = (!empty($product['offer_price']) && $product['offer_price'] > 0) ? $product['offer_price'] : $product['price'];

        // Check if item already exists in cart, if so, update quantity
        if (isset($cart[$productId])) {
            // Ensure we don't exceed actual stock
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            $cart[$productId]['quantity'] = min($newQuantity, $product['stock']);
        } else {
            // Add new item to session array. We store vendor_id here for the checkout split!
            $cart[$productId] = [
                'product_id' => $productId,
                'vendor_id'  => $vendorId,
                'name'       => $product['name'],
                'price'      => $price,
                'quantity'   => $quantity,
                'image'      => $product['image'],
                'max_stock'  => $product['stock']
            ];
        }

        $session->set('cart', $cart);
        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function updateCart()
    {
        // This acts as an AJAX endpoint to update cart totals without reloading the page
        $session = session();
        $cart = $session->get('cart') ?? [];
        
        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (int) $this->request->getPost('quantity');

        if (isset($cart[$productId])) {
            if ($quantity > 0 && $quantity <= $cart[$productId]['max_stock']) {
                $cart[$productId]['quantity'] = $quantity;
                $session->set('cart', $cart);

                // Recalculate auto-total for the AJAX response
                $total = array_reduce($cart, function($sum, $item) {
                    return $sum + ($item['price'] * $item['quantity']);
                }, 0);

                return $this->response->setJSON([
                    'status' => 'success', 
                    'new_item_total' => number_format($cart[$productId]['price'] * $quantity, 2),
                    'new_cart_total' => number_format($total, 2)
                ]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid quantity']);
    }

    public function removeFromCart($productId)
    {
        $session = session();
        $cart = $session->get('cart') ?? [];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $session->set('cart', $cart);
        }

        return redirect()->to('/cart')->with('success', 'Item removed.');
    }

    // --- CHECKOUT VIEWS ---
    public function checkoutView()
    {
        $session = session();
        $data['cart'] = $session->get('cart') ?? [];
        
        if (empty($data['cart'])) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        $data['total'] = array_reduce($data['cart'], function($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return view('shop/checkout', $data);
    }

    public function success()
    {
        return view('shop/success');
    }
}