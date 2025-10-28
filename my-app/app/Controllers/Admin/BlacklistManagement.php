<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BlacklistManagement extends BaseController
{
    public function index()
    {
        return view('admin/blacklist_management');
    }

    // public function addItem() { ... }
    // public function removeItem() { ... }
}