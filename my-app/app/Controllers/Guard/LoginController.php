<?php

namespace App\Controllers\Guard;

use App\Controllers\BaseController;
use App\Models\GuardModel; // Use the new Guard model

class LoginController extends BaseController
{
    /**
     * Displays the guard login page.
     */
    public function index(): string
    {
        // Check if already logged in
        if (session()->get('guard_logged_in')) {
            return redirect()->to(route_to('guard_dashboard'));
        }

        helper('form');

        return view('guard/guard_login');
    }

    /**
     * Processes the guard login form submission.
     */
    public function process()
    {
        helper('form');

        // 1. Validation Rules
        $rules = [
            'badge_number' => [
                'label' => 'Guard ID', // Match the view
                'rules' => 'required|string|max_length[100]',
                'errors' => [
                    'required' => 'The Guard ID field is required.',
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => 'The Password field is required.',
                    'min_length' => 'The Password must be at least 4 characters long.'
                ]
            ],
        ];

        // 2. Run Validation
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Get Validated Data
        $badgeNumber = $this->request->getPost('badge_number');
        $password = $this->request->getPost('password');

        // 4. Find Guard in Database
        $guardModel = new GuardModel();
        $guard = $guardModel->findByBadgeNumber($badgeNumber);

        // 5. Verify Guard and Password
        if ($guard === null) {
            return redirect()->back()->withInput()->with('error', 'Invalid Guard ID or Password.');
        }

        if (!password_verify($password, $guard->password_hash)) {
            return redirect()->back()->withInput()->with('error', 'Invalid Guard ID or Password.');
        }

        // --- NEW: Check for Approval ---
        if ($guard->is_active == 0) {
            return redirect()->back()->withInput()->with('error', 'Your account is pending approval from an administrator.');
        }
        // --- END NEW ---

        // 6. Login Successful - Set Session Data
        $session = session();

        // Build full name
        $fullName = $guard->firstname . ' ' . $guard->surname;
        if (!empty($guard->middlename)) {
            $fullName = $guard->firstname . ' ' . $guard->middlename . ' ' . $guard->surname;
        }

        $sessionData = [
            'guard_id'          => $guard->id,
            'guard_badge_number' => $guard->badge_number,
            'guard_name'        => $fullName, // Use the new full name
            'guard_logged_in'   => true,
        ];
        $session->set($sessionData);

        // 7. Redirect to Guard Dashboard (we will create this next)
        return redirect()->to(route_to('guard_dashboard'))->with('success', 'Login Successful!');
    }

    /**
     * Logs the guard out.
     */
    public function logout()
    {
        $session = session();
        $session->remove(['guard_id', 'guard_badge_number', 'guard_name', 'guard_logged_in']);
        return redirect()->to(route_to('guard_login'))->with('success', 'You have been logged out.');
    }
}

