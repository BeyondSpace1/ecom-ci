<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Ecom extends Migration
{
    public function up()
    {
        // 1. Users Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'       => ['type' => 'ENUM', 'constraint' => ['admin', 'vendor', 'customer'], 'default' => 'customer'],
            'status'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // 2. Vendors Table
        $this->forge->addField([
            'user_id'         => ['type' => 'INT', 'unsigned' => true],
            'store_name'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'store_logo'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'approval_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending'],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vendors');

        // 3. Categories Table
        $this->forge->addField([
            'id'     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'status' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories');

        // 4. Products Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'vendor_id'   => ['type' => 'INT', 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'price'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'offer_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'stock'       => ['type' => 'INT', 'default' => 0],
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('vendor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');

        // 5. Orders Table
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'customer_id'  => ['type' => 'INT', 'unsigned' => true],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status'       => ['type' => 'ENUM', 'constraint' => ['pending', 'processing', 'completed', 'cancelled'], 'default' => 'pending'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('customer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');

        // 6. Order Items Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_id'   => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'vendor_id'  => ['type' => 'INT', 'unsigned' => true],
            'price'      => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'quantity'   => ['type' => 'INT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('vendor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items');

        // 7. Payments Table
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_id'       => ['type' => 'INT', 'unsigned' => true],
            'amount'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'failed'], 'default' => 'pending'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        // Drop in reverse order to avoid foreign key constraint errors
        $this->forge->dropTable('payments', true);
        $this->forge->dropTable('order_items', true);
        $this->forge->dropTable('orders', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('categories', true);
        $this->forge->dropTable('vendors', true);
        $this->forge->dropTable('users', true);
    }
}
