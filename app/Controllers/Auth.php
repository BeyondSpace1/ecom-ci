<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;  
use App\Models\VendorModel;

class Auth extends BaseController
{
    public function login()
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            return $this->redirectUserByRole(session()->get('role'));
        }

        helper(['form']);
        return view('auth/login');
    }

    private function redirectUserByRole($role)
    {
        if ($role === 'admin') return redirect()->to('/admin/dashboard');
        if ($role === 'vendor') return redirect()->to('/vendor/dashboard');
        return redirect()->to('/'); // Customers go to homepage
    }

    public function loginAuth()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            if ($user['status'] == 0) {
                $session->setFlashdata('error', 'Your account is deactivated.');
                return redirect()->to('/login');
            }

            if (password_verify($password, $user['password'])) {
                $ses_data = [
                    'user_id'    => $user['id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                return $this->redirectUserByRole($user['role']);
            } else {
                $session->setFlashdata('error', 'Invalid Password.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email not found.');
            return redirect()->to('/login');
        }
    }

    public function register()
    {
        helper(['form']);
        return view('auth/register');
    }

    public function registerStore()
    {
        helper(['form']);
        
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|min_length[6]|max_length[100]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[255]',
            'role'     => 'required|in_list[customer,vendor]'
        ];

        if ($this->validate($rules)) {
            $userModel = new UserModel();
            
            $userData = [
                'name'      => $this->request->getVar('name'),
                'email'     => $this->request->getVar('email'),
                'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role'      => $this->request->getVar('role'),
                'status'    => 1,
                'created_at'=> date('Y-m-d H:i:s')
            ];

            $userModel->insert($userData);
            $userId = $userModel->getInsertID();

            if ($this->request->getVar('role') === 'vendor') {
                $vendorModel = new VendorModel();
                $vendorModel->insert([
                    'user_id'         => $userId,
                    'store_name'      => $this->request->getVar('name') . ' Store',
                    'approval_status' => 'pending'
                ]);
            }

            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        } else {
            $data['validation'] = $this->validator;
            return view('auth/register', $data);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
