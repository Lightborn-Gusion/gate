<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DailyReports extends BaseController
{
    /**
     * Shows the Daily Reports page, which loads the report view.
     */
    public function index()
    {
        return view('admin/daily_reports');
    }
}