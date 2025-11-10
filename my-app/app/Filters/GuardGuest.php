<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuardGuest implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // If 'guard_logged_in' session exists
        if (session()->get('guard_logged_in')) {
            // Redirect them to their dashboard
            return redirect()->to('guard/dashboard');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}