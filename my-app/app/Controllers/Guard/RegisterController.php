<?php

namespace App\Controllers\Guard;

use App\Controllers\BaseController;
use App\Models\GuardModel;

class RegisterController extends BaseController
{
    /**
     * Display the registration form.
     */
    public function index()
    {
        helper('form');
        return view('guard/guard_register');
    }

    /**
     * Process the registration form submission.
     */
    public function process()
    {
        helper('form');

        // 1. Validation Rules
        $rules = [
            'surname' => 'required|string|max_length[100]',
            'firstname' => 'required|string|max_length[100]',
            'middlename' => 'permit_empty|string|max_length[100]',
            'badge_number' => [
                'label' => 'Badge Number',
                'rules' => 'required|string|max_length[100]|is_unique[guards.badge_number]',
                'errors' => [
                    'required' => 'The Badge Number field is required.',
                    'is_unique' => 'This Badge Number is already registered.',
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => 'The Password field is required.',
                    'min_length' => 'Password must be at least 4 characters long.',
                ]
            ],
            'password_confirm' => [
                'label' => 'Confirm Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Please confirm your password.',
                    'matches' => 'Passwords do not match.',
                ]
            ],
        ];

        // 2. Run Validation
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // 3. Validation Passed - Prepare and Save Data
        try {
            $guardModel = new GuardModel();

            $data = [
                'surname'      => $this->request->getPost('surname'),
                'firstname'    => $this->request->getPost('firstname'),
                'middlename'   => $this->request->getPost('middlename'),
                'badge_number' => $this->request->getPost('badge_number'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                // 'is_active' is NOT set, so it uses the database default of '0' (Pending)
            ];

            // 4. Attempt to Save
            if ($guardModel->save($data)) {
                $message = 'Registration successful! Your account is now pending administrator approval.';

                return redirect()->to(route_to('guard_login'))
                    ->with('message', $message);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Registration failed due to a database issue. Please try again.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Guard registration failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred during registration. Please try again.');
        }
    }
}

