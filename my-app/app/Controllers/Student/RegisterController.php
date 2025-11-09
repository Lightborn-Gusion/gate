<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentModel;

class RegisterController extends BaseController
{
    /**
     * Display the registration form.
     */
    public function index()
    {
        helper('form');
        return view('student/student_create');
    }

    /**
     * Process the registration form submission.
     */
    public function process()
    {
        helper('form');

        // 1. Validation Rules - UPDATED
        $rules = [
            'name' => [ // --- NEW ---
                'label' => 'Full Name',
                'rules' => 'required|string|max_length[255]',
                'errors' => [
                    'required' => 'Your full name is required.',
                ]
            ],
            'school_id' => [
                'label' => 'School ID',
                'rules' => 'required|regex_match[/^TUPT-\d{2}-\d{4}$/]|is_unique[students.school_id]',
                'errors' => [
                    'required' => 'The School ID field is required.',
                    'regex_match' => 'School ID must follow the format TUPT-XX-XXXX.',
                    'is_unique' => 'This School ID is already registered.',
                ]
            ],
            'school_email' => [ // --- NEW ---
                'label' => 'School Email',
                'rules' => 'permit_empty|valid_email|max_length[255]|is_unique[students.school_email]',
                'errors' => [
                    'valid_email' => 'Please provide a valid email address.',
                    'is_unique' => 'This email is already registered.'
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
            // Validation failed, return to form with errors
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // 3. Validation Passed - Prepare and Save Data - UPDATED
        try {
            $studentModel = new StudentModel();

            $data = [
                'name'         => $this->request->getPost('name'), // --- NEW ---
                'school_id'    => $this->request->getPost('school_id'),
                'school_email' => $this->request->getPost('school_email') ?: null, // --- NEW --- (set to null if empty)
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            ];

            // 4. Attempt to Save
            if ($studentModel->save($data)) {
                // Success! Redirect to login page
                return redirect()->to(route_to('student_login'))
                    ->with('message', 'Registration successful! You can now log in.');
            } else {
                // Model's save method returned false
                log_message('error', 'Student registration save method returned false for school ID: ' . $data['school_id']);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Registration failed due to a database issue. Please try again.');
            }
        } catch (\Throwable $e) {
            // Catch any other exceptions
            log_message('error', 'Student registration failed for school ID: ' . $this->request->getPost('school_id') . ' Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred during registration. Please try again.');
        }
    }
}
