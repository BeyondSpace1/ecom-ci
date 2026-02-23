<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed the Admin User
        $userData = [
            'name'       => 'Super Admin',
            'email'      => 'admin@ecom.com',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT), // Securely hashed
            'role'       => 'admin',
            'status'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($userData);

        // 2. Seed Default Categories
        $categoryData = [
            ['name' => 'Electronics', 'status' => 1],
            ['name' => 'Clothing', 'status' => 1],
            ['name' => 'Home & Kitchen', 'status' => 1],
        ];
        $this->db->table('categories')->insertBatch($categoryData);
    }
}
