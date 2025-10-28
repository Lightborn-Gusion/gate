<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class PassManagement extends BaseController
{
    /**
     * Loads the main Visitor Pass Management interface.
     */
    public function index()
    {
        return view('admin/pass_management');
    }

    // public function createPass() { ... }
    // public function approvePass() { ... }
    // public function checkIn() { ... }
}