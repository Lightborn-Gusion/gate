<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class StudentAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // --- UPDATED: Check for your custom session variable ---
        if (! $session->get('student_logged_in')) {
            // --- END UPDATE ---

            // Save the URL they were trying to access
            $session->setTempdata('beforeLoginUrl', current_url());

            // Redirect to the student login page
            return redirect()->route('student_login');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}