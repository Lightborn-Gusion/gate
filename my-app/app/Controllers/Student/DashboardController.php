<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\ItemLogModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // 1. Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return redirect()->to(route_to('student_login'))
                ->with('error', 'Please log in to access the dashboard.');
        }

        // 2. Get student ID from session
        $studentId = session()->get('student_id');

        // 3. Load Models
        $itemModel = new ItemModel();
        $logModel = new ItemLogModel();

        // 4. Fetch Data
        // --- UPDATED: Fetch all non-deleted items first ---
        $allItems = $itemModel->where('student_id', $studentId)->findAll();

        // --- NEW: Filter for active items (not missing or stolen) ---
        $activeItems = array_filter($allItems, fn($item) => in_array($item['status'], ['inside', 'outside']));

        // --- NEW: Filter for items currently 'inside' campus ---
        $itemsInside = array_filter($allItems, fn($item) => $item['status'] === 'inside');

        // Find all logs for all items (active or not)
        $itemIds = array_column($allItems, 'id');
        $latestLogs = [];
        if (! empty($itemIds)) {
            $latestLogs = $logModel->whereIn('item_id', $itemIds)
                ->orderBy('timestamp', 'DESC')
                ->findAll(5); // Get the 5 most recent logs
        }

        // 5. Prepare data for the view - UPDATED
        $data = [
            'studentName'     => session()->get('student_name'),
            'registeredItems' => $allItems,    // <-- "My Items" list (all)
            'activeItems'     => $activeItems, // <-- Dashboard grid & "Remove" list
            'itemsInside'     => $itemsInside, // <-- "Report" list
            'latestLogs'      => $latestLogs,
        ];

        // 6. Load View
        return view('student/student_main', $data);
    }
}