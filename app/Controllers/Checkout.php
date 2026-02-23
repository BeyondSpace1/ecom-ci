<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use App\Models\PaymentModel;

class Checkout extends BaseController
{
    public function processCheckout()
    {
        $session = session();
        $cart = $session->get('cart'); // Assuming cart is stored in session as an array of items
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // START TRANSACTION

        try {
            // 1. Calculate overall total
            $totalAmount = array_reduce($cart, function($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            // 2. Create Main Order Record
            $orderModel = new OrderModel();
            $orderId = $orderModel->insert([
                'customer_id'  => $session->get('user_id'),
                'total_amount' => $totalAmount,
                'status'       => 'processing',
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // 3. Process Cart Items (Split by Vendor & Reduce Stock)
            $orderItemModel = new OrderItemModel();
            $productModel = new ProductModel();

            foreach ($cart as $item) {
                // Insert individual order items tracking the vendor
                $orderItemModel->insert([
                    'order_id'   => $orderId,
                    'product_id' => $item['product_id'],
                    'vendor_id'  => $item['vendor_id'],
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity']
                ]);

                // Reduce Stock dynamically
                $productModel->set('stock', 'stock - ' . (int)$item['quantity'], false)
                             ->where('id', $item['product_id'])
                             ->update();
            }

            // 4. Create Payment Simulation Record
            $paymentModel = new PaymentModel();
            $paymentModel->insert([
                'order_id'       => $orderId,
                'amount'         => $totalAmount,
                'payment_status' => 'paid', // Simulated as paid immediately
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            $db->transComplete(); // COMMIT TRANSACTION

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

            // Clear Cart
            $session->remove('cart');
            return redirect()->to('/checkout/success')->with('message', 'Order placed successfully!');

        } catch (\Exception $e) {
            $db->transRollback(); // ROLLBACK ON ERROR
            return redirect()->to('/checkout')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
