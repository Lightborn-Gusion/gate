<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Filters\SessionAuth;

class StudentAuth extends SessionAuth implements FilterInterface
{
    /**
     * @param array|null $arguments
     *
     * @return ResponseInterface|RequestInterface
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // --- THIS LINE IS UPDATED ---
        if (! service('auth')->loggedIn()) {
            // --- END UPDATE ---

            if (service('auth')->remember()) {
                // User has a remember-me cookie, attempt to log them in
                $user = service('auth')->getRememberedUser();
                if ($user) {
                    service('auth')->login($user);
                    return $request;
                }
            }

            $session = session();
            $session->setTempdata('beforeLoginUrl', current_url());

            return redirect()->route('student_login');
        }

        return $request;
    }
}