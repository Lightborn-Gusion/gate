<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    /**
     * Displays the student's profile page.
     */
    public function index(): string
    {
        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return redirect()->to(route_to('student_login'))
                ->with('error', 'Please log in to access your profile.');
        }

        $studentId = session()->get('student_id');
        $studentModel = new StudentModel();

        // Fetch the latest student data from the DB
        $student = $studentModel->find($studentId);

        if (! $student) {
            // This should not happen if they are logged in, but as a safeguard
            session()->destroy();
            return redirect()->to(route_to('student_login'))
                ->with('error', 'Could not find your profile. Please log in again.');
        }

        return view('student/student_profile', ['student' => $student]);
    }

    /**
     * Handles the "Update Details" AJAX request
     */
    public function updateDetails(): ResponseInterface
    {
        // Check if it's an AJAX request and user is logged in
        if (! $this->request->isAJAX() || ! session()->get('student_logged_in')) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $studentId = session()->get('student_id');

        // 1. Validation Rules
        $rules = [
            'name' => 'required|string|max_length[255]',
            'school_email' => 'permit_empty|valid_email|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400);
        }

        // 2. Prepare and Save Data
        try {
            $studentModel = new StudentModel();
            $data = [
                'name'         => $this->request->getPost('name'),
                'school_email' => $this->request->getPost('school_email'),
            ];

            if ($studentModel->update($studentId, $data)) {
                // IMPORTANT: Update the name in the session
                session()->set('student_name', $data['name']);

                return $this->response->setJSON(['success' => true, 'message' => 'Profile details updated successfully!']);
            }

            return $this->response->setJSON(['success' => false, 'errors' => $studentModel->errors()])
                ->setStatusCode(500);

        } catch (\Exception $e) {
            log_message('error', '[ProfileController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not update profile. Please try again.']])
                ->setStatusCode(500);
        }
    }

    /**
     * Handles the "Update Password" AJAX request
     */
    public function updatePassword(): ResponseInterface
    {
        // Check if it's an AJAX request and user is logged in
        if (! $this->request->isAJAX() || ! session()->get('student_logged_in')) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $studentId = session()->get('student_id');

        // 1. Validation Rules
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[4]',
            'confirm_new_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400);
        }

        // 2. Verify Current Password
        try {
            $studentModel = new StudentModel();
            $student = $studentModel->find($studentId);

            if (! $student) {
                return $this->response->setJSON(['success' => false, 'errors' => ['account' => 'Student account not found.']])
                    ->setStatusCode(404);
            }

            $currentPassword = $this->request->getPost('current_password');

            if (! password_verify($currentPassword, $student->password_hash)) {
                // Incorrect current password
                return $this->response->setJSON(['success' => false, 'errors' => ['current_password' => 'Your current password does not match.']])
                    ->setStatusCode(400);
            }

            // 3. Save New Password
            $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

            if ($studentModel->update($studentId, ['password_hash' => $newPasswordHash])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Password changed successfully!']);
            }

            return $this->response->setJSON(['success' => false, 'errors' => $studentModel->errors()])
                ->setStatusCode(500);

        } catch (\Exception $e) {
            log_message('error', '[ProfileController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not update password. Please try again.']])
                ->setStatusCode(500);
        }
    }
}
