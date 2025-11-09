<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\StudentPasswordResetModel;

class PasswordController extends BaseController
{
    /**
     * Displays the "forgot password" form.
     */
    public function forgot()
    {
        return view('student/forgot_password');
    }

    /**
     * Processes the "forgot password" request.
     * --- UPDATED ---
     */
    public function processForgot()
    {
        // 1. Validate email
        $rules = [
            'school_email' => [
                'label'  => 'School Email',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'The School Email field is required.',
                    'valid_email' => 'Please enter a valid email address.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('school_email');

        // 2. Find student by email
        $studentModel = new StudentModel();
        $student      = $studentModel->where('school_email', $email)->first();

        if ($student === null) {
            // IMPORTANT: Show a generic message to prevent email enumeration
            // --- UPDATED: Redirect to reset page with message ---
            return redirect()->to('student/password/reset')->with('message', 'If an account with that email exists, a reset code has been sent.');
        }

        // 3. Generate Token and Save to DB
        $resetModel = new StudentPasswordResetModel();

        // Delete any old tokens for this user
        $resetModel->where('student_email', $email)->delete();

        // --- UPDATED: Generate 6-character simple code ---
        $allowedChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $allowedChars[random_int(0, strlen($allowedChars) - 1)];
        }
        // --- END UPDATED ---

        $resetModel->save([
            'student_email' => $email,
            'token'         => $token,
            'expires_at'    => date('Y-m-d H:i:s', time() + 3600), // 1 hour expiry
        ]);

        // 4. Send Email
        $emailService = \Config\Services::email();

        // --- UPDATED: Send code, not link ---
        $message = "Hello {$student->name},\n\n"
            . "You have requested to reset your password. Your reset code is:\n\n"
            . $token . "\n\n"
            . "This code will expire in 1 hour.\n\n"
            . "If you did not request this, please ignore this email.";

        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Code'); // --- UPDATED Subject ---
        $emailService->setMessage($message);

        if (! $emailService->send()) {
            log_message('error', 'Failed to send password reset email: ' . $emailService->printDebugger(['headers']));
            return redirect()->back()->with('error', 'Could not send reset email. Please try again later.');
        }

        // --- UPDATED: Redirect to reset page with message ---
        return redirect()->to('student/password/reset')->with('message', 'A reset code has been sent to your email. Please enter it below.');
    }

    /**
     * Displays the "reset password" form.
     * --- UPDATED ---
     */
    public function reset()
    {
        // --- UPDATED: No token in URL, just show the form ---
        return view('student/reset_password');
    }

    /**
     * Processes the "reset password" form submission.
     * --- UPDATED ---
     */
    public function processReset()
    {
        // 1. Validation
        $rules = [
            'token' => [
                'label' => 'Reset Code', // --- UPDATED Label ---
                'rules' => 'required',
            ],
            'password' => [
                'label'  => 'New Password',
                'rules'  => 'required|min_length[4]', // --- UPDATED: min_length 4 ---
                'errors' => [
                    'min_length' => 'Your new password must be at least 4 characters long.',
                ],
            ],
            'password_confirm' => [
                'label'  => 'Confirm Password',
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'matches' => 'The passwords do not match.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token    = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        // 2. Validate Token again
        $resetModel = new StudentPasswordResetModel();
        $tokenData  = $resetModel->where('token', $token)->first();

        if ($tokenData === null) {
            return redirect()->back()->with('error', 'Invalid password reset code.'); // --- UPDATED Message ---
        }

        if (strtotime($tokenData->expires_at) < time()) {
            $resetModel->delete($tokenData->id);
            return redirect()->to('student/forgot-password')->with('error', 'Password reset code has expired. Please request a new one.'); // --- UPDATED Message ---
        }

        // 3. Find Student and Update Password
        $studentModel = new StudentModel();
        $student      = $studentModel->where('school_email', $tokenData->student_email)->first();

        if ($student === null) {
            // Should not happen if token is valid, but good to check
            return redirect()->to('student/login')->with('error', 'An unknown error occurred.');
        }

        // Hash new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $studentModel->update($student->id, ['password_hash' => $hashedPassword]);

        // 4. Invalidate Token (delete it)
        $resetModel->delete($tokenData->id);

        // 5. Redirect to Login
        return redirect()->to('student/login')->with('success', 'Your password has been successfully reset. You may now log in.');
    }
}