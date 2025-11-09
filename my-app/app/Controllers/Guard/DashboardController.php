<?php

namespace App\Controllers\Guard;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\ItemLogModel;
use App\Models\StudentModel;
use App\Models\GuardModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    /**
     * Display the guard dashboard page.
     */
    public function index()
    {
        if (! session()->get('guard_logged_in')) {
            return redirect()->to(route_to('guard_login'));
        }

        $logModel = new ItemLogModel();
        $studentModel = new StudentModel();
        $guardModel = new GuardModel();

        // Fetch latest 20 logs
        $logs = $logModel
            ->orderBy('timestamp', 'DESC')
            ->findAll(20);

        // We need to enrich these logs with item, student, and guard names
        $latestLogs = [];
        foreach ($logs as $log) {
            $item = (new ItemModel())->find($log['item_id']);
            if ($item) {
                $student = $studentModel->find($item['student_id']);
                $guard = $guardModel->find($log['guard_id']);

                $log['item_name'] = $item['item_name'];
                $log['serial_number'] = $item['serial_number'];
                $log['student_name'] = $student ? $student->name : 'N/A';
                $log['guard_name'] = $guard ? ($guard->firstname . ' ' . $guard->surname) : 'N/A';

                $latestLogs[] = $log;
            }
        }

        $data = [
            'guardName'  => session()->get('guard_name'),
            'latestLogs' => $latestLogs
        ];

        return view('guard/guard_dashboard', $data);
    }

    /**
     * Handles the AJAX request to fetch item details from a scan.
     */
    public function fetchItemDetails(): ResponseInterface
    {
        if (! $this->request->isAJAX() || ! session()->get('guard_logged_in')) {
            return $this->response->setStatusCode(403);
        }

        $scannedData = $this->request->getPost('scanned_data');

        // --- THIS IS THE FIX ---
        // Old, incorrect code:
        // parse_str(str_replace(';', '&', $scannedData), $data);

        // New, correct code:
        $data = [];
        $parts = explode(';', $scannedData); // Split into ["ITEM_ID:1", "SERIAL:S6R5"]
        foreach ($parts as $part) {
            $subParts = explode(':', $part, 2); // Split into [key, value]
            if (count($subParts) === 2) {
                $data[$subParts[0]] = $subParts[1];
            }
        }
        // --- END OF FIX ---


        if (! isset($data['ITEM_ID']) || ! is_numeric($data['ITEM_ID'])) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Invalid pass format. ITEM_ID not found.',
                'new_token' => csrf_hash()
            ])->setStatusCode(400);
        }

        $itemId = $data['ITEM_ID'];
        $itemModel = new ItemModel();
        $studentModel = new StudentModel();

        $item = $itemModel->find($itemId);

        if (! $item) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Item not found in the database.',
                'new_token' => csrf_hash()
            ])->setStatusCode(404);
        }

        $student = $studentModel->find($item['student_id']);

        if (! $student) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Associated student not found.',
                'new_token' => csrf_hash()
            ])->setStatusCode(404);
        }

        // Success! Send back the data.
        return $this->response->setJSON([
            'success'   => true,
            'item' => [
                'item_id'       => $item['id'],
                'item_name'     => $item['item_name'],
                'serial_number' => $item['serial_number'],
                'current_status'=> $item['status'],
                'image_path'    => base_url('uploads/item_images/' . $item['image_path'])
            ],
            'student' => [
                'name'      => $student->name,
                'school_id' => $student->school_id
            ],
            'new_token' => csrf_hash()
        ]);
    }

    /**
     * Handles the AJAX request to CONFIRM the scan (log the item).
     */
    public function confirmScan(): ResponseInterface
    {
        if (! $this->request->isAJAX() || ! session()->get('guard_logged_in')) {
            return $this->response->setStatusCode(403);
        }

        $itemId = $this->request->getPost('item_id');
        $guardId = session()->get('guard_id');

        $itemModel = new ItemModel();
        $logModel = new ItemLogModel();

        $item = $itemModel->find($itemId);

        if (! $item) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Item not found. Could not process log.',
                'new_token' => csrf_hash()
            ])->setStatusCode(404);
        }

        // Determine new status and log type
        $newStatus = ($item['status'] === 'outside') ? 'inside' : 'outside';
        $logType = ($item['status'] === 'outside') ? 'entry' : 'exit';

        // 1. Update the item's status
        $itemModel->update($itemId, ['status' => $newStatus]);

        // 2. Create a new log entry
        $logModel->save([
            'item_id'   => $itemId,
            'guard_id'  => $guardId,
            'log_type'  => $logType,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        $message = ($newStatus === 'inside')
            ? 'Item successfully checked IN'
            : 'Item successfully checked OUT';

        return $this->response->setJSON([
            'success'    => true,
            'message'    => $message,
            'new_status' => $newStatus,
            'new_token'  => csrf_hash()
        ]);
        $newStatus = ($item['status'] === 'outside') ? 'inside' : 'outside';
        $logType = ($item['status'] === 'outside') ? 'entry' : 'exit';

        // 1. Update the item's status
        $itemModel->update($itemId, ['status' => $newStatus]);

        // 2. Create a new log entry
        $logModel->save([
            'item_id'   => $itemId,
            'guard_id'  => $guardId,
            'log_type'  => $logType,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        // --- NEW: Build HTML for the new log row ---
        $logId = $logModel->getInsertID();
        $newLog = $logModel->find($logId);
        $newLogHtml = '';

        if ($newLog) {
            $item = (new ItemModel())->find($newLog['item_id']);
            $student = (new StudentModel())->find($item['student_id']);
            $guard = (new GuardModel())->find($newLog['guard_id']);

            $logData = [
                'log_type'      => $newLog['log_type'],
                'item_name'     => $item['item_name'],
                'serial_number' => $item['serial_number'],
                'student_name'  => $student ? $student->name : 'N/A',
                'guard_name'    => $guard ? ($guard->firstname . ' ' . $guard->surname) : 'N/A',
                'timestamp'     => $newLog['timestamp']
            ];

            $icon = ($logData['log_type'] === 'entry')
                ? '<i class="ti ti-arrow-right text-success log-icon" title="Entry"></i>'
                : '<i class="ti ti-arrow-left text-danger log-icon" title="Exit"></i>';

            $timestamp = date('M d, h:i A', strtotime(esc($logData['timestamp'])));

            $newLogHtml = "
                <tr>
                    <td class='text-center'>{$icon}</td>
                    <td>
                        <strong>" . esc($logData['item_name']) . "</strong>
                        <br>
                        <small class='text-muted'>" . esc($logData['serial_number']) . "</small>
                    </td>
                    <td>" . esc($logData['student_name']) . "</td>
                    <td>" . esc($logData['guard_name']) . "</td>
                    <td>{$timestamp}</td>
                </tr>
            ";
        }
        // --- END NEW ---

        $message = ($newStatus === 'inside')
            ? 'Item successfully checked IN'
            : 'Item successfully checked OUT';

        return $this->response->setJSON([
            'success'    => true,
            'message'    => $message,
            'new_status' => $newStatus,
            'new_token'  => csrf_hash(),
            'new_log_html' => $newLogHtml // Send new HTML to frontend
        ]);
    }
}

