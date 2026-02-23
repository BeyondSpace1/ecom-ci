<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\VendorModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\OrderModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $vendorModel = new VendorModel();
        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        $userModel = new UserModel();

        // Sum all orders that haven't been cancelled
        $revenue = $orderModel->selectSum('total_amount')->where('status !=', 'cancelled')->first();

        $data = [
            'total_vendors'  => $vendorModel->where('approval_status', 'approved')->countAllResults(),
            'total_products' => $productModel->countAllResults(),
            'total_orders'   => $orderModel->countAllResults(),
            'total_revenue'  => $revenue['total_amount'] ?? 0.00,
            'pending_vendors'=> $vendorModel->where('approval_status', 'pending')->countAllResults()
        ];

        return view('admin/dashboard', $data);
    }

    // --- VENDOR MANAGEMENT ---
    public function vendors()
    {
        $vendorModel = new VendorModel();
        // Join with users to get email and name
        $data['vendors'] = $vendorModel->select('vendors.*, users.email, users.name')
                                       ->join('users', 'users.id = vendors.user_id')
                                       ->findAll();
        return view('admin/vendors', $data);
    }

    public function approveVendor($userId)
    {
        $vendorModel = new VendorModel();
        $vendorModel->update($userId, ['approval_status' => 'approved']);
        return redirect()->back()->with('success', 'Vendor approved successfully.');
    }

    public function rejectVendor($userId)
    {
        $vendorModel = new VendorModel();
        $vendorModel->update($userId, ['approval_status' => 'rejected']);
        return redirect()->back()->with('error', 'Vendor application rejected.');
    }

    // --- USER MANAGEMENT ---
    public function users()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->where('role !=', 'admin')->findAll();
        return view('admin/users', $data);
    }

    public function toggleUserStatus($userId)
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if ($user) {
            $newStatus = ($user['status'] == 1) ? 0 : 1;
            $userModel->update($userId, ['status' => $newStatus]);
            $msg = $newStatus == 1 ? 'User activated.' : 'User deactivated.';
            return redirect()->back()->with('success', $msg);
        }
        return redirect()->back()->with('error', 'User not found.');
    }

    // --- CATEGORY MANAGEMENT ---
    public function categories()
    {
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->findAll();
        return view('admin/categories', $data);
    }

    public function addCategory()
    {
        $categoryModel = new CategoryModel();
        $name = $this->request->getPost('name');
        
        if (!empty($name)) {
            $categoryModel->insert(['name' => $name, 'status' => 1]);
            return redirect()->back()->with('success', 'Category added.');
        }
        return redirect()->back()->with('error', 'Category name is required.');
    }

    // --- VIEW ONLY DATA ---
    public function products()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->select('products.*, vendors.store_name, categories.name as category_name')
                                         ->join('vendors', 'vendors.user_id = products.vendor_id')
                                         ->join('categories', 'categories.id = products.category_id')
                                         ->findAll();
        return view('admin/products', $data);
    }

    public function orders()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->select('orders.*, users.name as customer_name')
                                     ->join('users', 'users.id = orders.customer_id')
                                     ->orderBy('orders.created_at', 'DESC')
                                     ->findAll();
        return view('admin/orders', $data);
    }
}