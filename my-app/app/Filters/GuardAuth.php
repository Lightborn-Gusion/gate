<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuardAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // --- UPDATED: Check for a guard-specific session variable ---
        if (! $session->get('guard_logged_in')) {
            // --- END UPDATE ---

            $session->setTempdata('beforeLoginUrl', current_url());

            // Redirect to the guard login page
            return redirect()->route('guard_login');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}