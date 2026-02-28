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

    // public function addProduct()
    // {
        
    //     $productModel = new \App\Models\ProductModel();
        
    //     // 1. Grab the uploaded file
    //     $file = $this->request->getFile('image');
    //     $imageName = ''; // Default empty

    //     // 2. Check if a file was uploaded and is valid
    //     if ($file && $file->isValid() && !$file->hasMoved()) {
    //         // Generate a random secure name
    //         $imageName = $file->getRandomName();
    //         // Move it to public/uploads/products/
    //         $file->move(FCPATH . 'uploads/products', $imageName);
    //     }

    //     // 3. Save to database
    //     $data = [
    //         'vendor_id'   => session()->get('user_id'),
    //         'category_id' => $this->request->getPost('category_id'),
    //         'name'        => $this->request->getPost('name'),
    //         'price'       => $this->request->getPost('price'),
    //         'offer_price' => $this->request->getPost('offer_price'),
    //         'stock'       => $this->request->getPost('stock'),
    //         'image'       => $imageName, // Save the new file name!
    //         'status'      => 1
    //     ];

    //     $productModel->insert($data);
    //     return redirect()->to('/vendor/products')->with('success', 'Product added!');
    
    // }
    public function addProduct()
    {
        $categoryModel = new \App\Models\CategoryModel();
        
        // Fetch only ACTIVE categories for the dropdown
        $data['categories'] = $categoryModel->where('status', 1)->findAll();
        
        return view('vendor/add_product', $data);
    }

    // public function storeProduct() 
    // {
    //     // 1. Strict Validation: Stop the process if the category is missing
    //     if (!$this->validate([
    //         'category_id' => 'required',
    //         'name'        => 'required',
    //         'price'       => 'required|numeric'
    //     ])) {
    //         return redirect()->back()->withInput()->with('error', 'Please ensure all required fields, including the Category, are filled out.');
    //     }

    //     $productModel = new \App\Models\ProductModel();
        
    //     // 2. Handle the Image Upload Securely
    //     $file = $this->request->getFile('image');
    //     $imageName = '';
        
    //     if ($file && $file->isValid() && !$file->hasMoved()) {
    //         $imageName = $file->getRandomName(); // Secure rename
    //         $file->move(FCPATH . 'uploads/products', $imageName); // Move to public folder
    //     }

    //     // 3. Save to Database
    //     $data = [
    //         'vendor_id'   => session()->get('user_id'),
    //         'category_id' => $this->request->getPost('category_id'),
    //         'name'        => $this->request->getPost('name'),
    //         'price'       => $this->request->getPost('price'),
    //         'offer_price' => $this->request->getPost('offer_price'),
    //         'stock'       => $this->request->getPost('stock'),
    //         'image'       => $imageName,
    //         'status'      => 1
    //     ];

    //     $productModel->insert($data);
    //     return redirect()->to('/vendor/products')->with('success', 'Product published successfully!');
    // }

    public function storeProduct()
    {
        // 1. Basic Form Validation
        if (!$this->validate([
            'category_id' => 'required',
            'name'        => 'required',
            'price'       => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Please fill all required fields.');
        }

        $productModel = new \App\Models\ProductModel();
        
        // 2. File Upload Handling with DEBUGGING
        $file = $this->request->getFile('image');
        $imageName = ''; // Default to empty string if no image

        // Check if a file was uploaded
        if ($file && $file->getName() !== '') {
            // Check if the upload failed (e.g., file too large, server config issue)
            if (!$file->isValid()) {
                return redirect()->back()->withInput()->with('error', 'Image Upload Failed: ' . $file->getErrorString());
            }

            // If valid, rename and move it
            if (!$file->hasMoved()) {
                $imageName = $file->getRandomName();
                // FCPATH points to the 'public' folder. 
                $file->move(FCPATH . 'uploads/products', $imageName);
            }
        }

        // 3. Save Data to Database
        $data = [
            'vendor_id'   => session()->get('user_id'),
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'offer_price' => $this->request->getPost('offer_price'),
            'stock'       => $this->request->getPost('stock'),
            'image'       => $imageName,
            'status'      => 1
        ];

        $productModel->insert($data);
        return redirect()->to('/vendor/products')->with('success', 'Product published successfully!');
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

    public function toggleProductStatus($id)
    {
        $productModel = new \App\Models\ProductModel();
        // Ensure the vendor owns the product (Security)
        $product = $productModel->where('vendor_id', session()->get('user_id'))->find($id);

        if ($product) {
            $newStatus = ($product['status'] == 1) ? 0 : 1;
            $productModel->update($id, ['status' => $newStatus]);
            return redirect()->back()->with('success', 'Product visibility updated.');
        }
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    public function updateOrderStatus($itemId)
    {
        $orderItemModel = new \App\Models\OrderItemModel();
        
        // Strict Isolation: Ensure the vendor owns this specific order item
        $item = $orderItemModel->where('vendor_id', session()->get('user_id'))->find($itemId);

        if ($item) {
            $status = $this->request->getPost('status');
            $orderItemModel->update($itemId, ['status' => $status]);
            return redirect()->back()->with('success', 'Order status updated to ' . ucfirst($status));
        }
        
        return redirect()->back()->with('error', 'Unauthorized action.');
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
