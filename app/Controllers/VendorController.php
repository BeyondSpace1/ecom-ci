<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\VendorModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\OrderItemModel;

class VendorController extends BaseController
{
    private $vendorId;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        // Store the logged-in user's ID to strictly isolate all queries
        $this->vendorId = session()->get('user_id');
    }

    public function dashboard()
    {
        $productModel = new ProductModel();
        $orderItemModel = new OrderItemModel();

        // Strict Isolation: Only get stats for THIS vendor
        $totalProducts = $productModel->where('vendor_id', $this->vendorId)->countAllResults();
        
        $salesData = $orderItemModel->selectSum('quantity')
                                    ->selectSum('price') // Price here represents total line item price
                                    ->where('vendor_id', $this->vendorId)
                                    ->first();

        $data = [
            'total_products' => $totalProducts,
            'items_sold'     => $salesData['quantity'] ?? 0,
            'total_earnings' => $salesData['price'] ?? 0.00,
        ];

        return view('vendor/dashboard', $data);
    }

    // --- PROFILE & LOGO UPLOAD ---
    public function profile()
    {
        $vendorModel = new VendorModel();
        $data['vendor'] = $vendorModel->find($this->vendorId);
        return view('vendor/profile', $data);
    }

    public function updateProfile()
    {
        $vendorModel = new VendorModel();
        $vendor = $vendorModel->find($this->vendorId);

        $updateData = ['store_name' => $this->request->getPost('store_name')];

        // Handle Image Upload with Validation
        $file = $this->request->getFile('store_logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $validationRule = [
                'store_logo' => [
                    'rules' => 'uploaded[store_logo]|is_image[store_logo]|mime_in[store_logo,image/jpg,image/jpeg,image/png]|max_size[store_logo,2048]',
                ],
            ];

            if ($this->validate($validationRule)) {
                // Delete old logo if it exists
                if (!empty($vendor['store_logo']) && file_exists(FCPATH . 'uploads/logos/' . $vendor['store_logo'])) {
                    unlink(FCPATH . 'uploads/logos/' . $vendor['store_logo']);
                }

                // Rename and move new file
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/logos', $newName);
                $updateData['store_logo'] = $newName;
            } else {
                return redirect()->back()->with('error', $this->validator->listErrors());
            }
        }

        $vendorModel->update($this->vendorId, $updateData);
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // --- PRODUCT MANAGEMENT ---
    public function products()
    {
        $productModel = new ProductModel();
        // Strict Isolation
        $data['products'] = $productModel->select('products.*, categories.name as category_name')
                                         ->join('categories', 'categories.id = products.category_id')
                                         ->where('products.vendor_id', $this->vendorId)
                                         ->findAll();
        return view('vendor/products', $data);
    }

    public function addProduct()
    {
        if ($this->request->getMethod() === 'POST') {
            $productModel = new ProductModel();

            $data = [
                'vendor_id'   => $this->vendorId,
                'category_id' => $this->request->getPost('category_id'),
                'name'        => $this->request->getPost('name'),
                'price'       => $this->request->getPost('price'),
                'offer_price' => $this->request->getPost('offer_price') ?: null,
                'stock'       => $this->request->getPost('stock'),
                'status'      => 1
            ];

            $file = $this->request->getFile('image');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/products', $newName);
                $data['image'] = $newName;
            }

            $productModel->insert($data);
            return redirect()->to('/vendor/products')->with('success', 'Product added successfully.');
        }

        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->where('status', 1)->findAll();
        return view('vendor/add_product', $data);
    }

    public function deleteProduct($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->where('vendor_id', $this->vendorId)->find($id); // Ensure ownership

        if ($product) {
            // Delete image file
            if (!empty($product['image']) && file_exists(FCPATH . 'uploads/products/' . $product['image'])) {
                unlink(FCPATH . 'uploads/products/' . $product['image']);
            }
            $productModel->delete($id);
            return redirect()->back()->with('success', 'Product deleted.');
        }
        return redirect()->back()->with('error', 'Product not found or access denied.');
    }

    // --- AJAX PRODUCT SEARCH ---
    public function searchProductsAjax()
    {
        $query = $this->request->getGet('query');
        $productModel = new ProductModel();
        
        // Return JSON response for AJAX, strictly isolated
        $products = $productModel->where('vendor_id', $this->vendorId)
                                 ->like('name', $query)
                                 ->findAll();
                                 
        return $this->response->setJSON($products);
    }

    // --- VENDOR ORDERS ---
    public function orders()
    {
        $orderItemModel = new OrderItemModel();
        // Join with main orders table to get status, and products to get the name
        $data['order_items'] = $orderItemModel->select('order_items.*, orders.status, orders.created_at, products.name as product_name')
                                              ->join('orders', 'orders.id = order_items.order_id')
                                              ->join('products', 'products.id = order_items.product_id')
                                              ->where('order_items.vendor_id', $this->vendorId)
                                              ->orderBy('orders.created_at', 'DESC')
                                              ->findAll();

        return view('vendor/orders', $data);
    }
}
