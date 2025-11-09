<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentModel;

class LoginController extends BaseController
{
    /**
     * Displays the student login page.
     */
    public function index(): string
    {
        return view('student/student_login');
    }

    /**
     * Processes the student login form submission.
     */
    public function process()
    {
        // 1. Validation Rules
        $rules = [
            'school_id' => [
                'label' => 'School ID',
                'rules' => 'required|regex_match[/^TUPT-\d{2}-\d{4}$/]',
                'errors' => [
                    'required' => 'The School ID field is required.',
                    'regex_match' => 'The School ID must be in the format TUPT-XX-XXXX.'
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
        $schoolId = $this->request->getPost('school_id');
        $password = $this->request->getPost('password');

        // 4. Find Student in Database
        $studentModel = new StudentModel();
        $student = $studentModel->findBySchoolId($schoolId);

        // 5. Verify Student and Password
        if ($student === null) {
            return redirect()->back()->withInput()->with('error', 'Invalid School ID or Password.');
        }

        if (!password_verify($password, $student->password_hash)) {
            return redirect()->back()->withInput()->with('error', 'Invalid School ID or Password.');
        }

        // 6. Login Successful - Set Session Data - UPDATED
        $session = session();
        $sessionData = [
            'student_id'        => $student->id,
            'student_name'      => $student->name, // --- NEW ---
            'student_school_id' => $student->school_id,
            'student_logged_in' => true,
        ];
        $session->set($sessionData);

        // 7. Redirect to Student Dashboard
        return redirect()->to('/student/dashboard')->with('success', 'Login Successful!');
    }

    /**
     * Logs the student out.
     */
    public function logout()
    {
        $session = session();
        // --- UPDATED --- (also remove student_name)
        $session->remove(['student_id', 'student_name', 'student_school_id', 'student_logged_in']);
        return redirect()->to('/student/login')->with('success', 'You have been logged out.');
    }
}
