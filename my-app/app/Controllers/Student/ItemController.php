<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class ItemController extends BaseController
{
    /**
     * Handle the item registration form submission via AJAX
     */
    public function create(): ResponseInterface
    {
        // Check if it's an AJAX request
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Authentication required.'])
                ->setStatusCode(401);
        }

        // 1. Validation Rules
        $rules = [
            'item_name'     => 'required|string|max_length[255]',
            // --- UPDATED: Use new custom rule ---
            'serial_number' => 'required|string|max_length[255]|is_unique_soft_delete[items,serial_number]',
            'category'      => 'required|string|max_length[100]',
            'description'   => 'permit_empty|string|max_length[500]',
            'item_image' => [
                'label' => 'Item Image',
                'rules' => 'uploaded[item_image]'
                    . '|is_image[item_image]'
                    . '|mime_in[item_image,image/jpg,image/jpeg,image/png,image/gif]'
                    . '|max_size[item_image,10240]', // --- UPDATED: Increased to 10MB ---
            ],
        ];

        if (! $this->validate($rules)) {
            // Return validation errors as JSON
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400); // Bad Request
        }

        // 2. Handle File Upload
        $img = $this->request->getFile('item_image');
        $imagePath = null;

        if ($img && $img->isValid() && ! $img->hasMoved()) {
            $newName = $img->getRandomName();

            // --- Save to public/uploads/item_images ---
            if ($img->move(FCPATH . 'uploads/item_images', $newName)) {
                $imagePath = $newName; // We only store the filename
            } else {
                // File move failed
                return $this->response->setJSON(['success' => false, 'errors' => ['item_image' => $img->getErrorString()]])
                    ->setStatusCode(500);
            }
        }

        // 3. Save to Database
        try {
            $itemModel = new ItemModel();

            $data = [
                'student_id'    => session()->get('student_id'), // Get logged-in student's ID
                'item_name'     => $this->request->getPost('item_name'),
                'serial_number' => $this->request->getPost('serial_number'),
                'category'      => $this->request->getPost('category'),
                'description'   => $this->request->getPost('description'),
                'image_path'    => $imagePath,
                'status'        => 'outside', // Default status
            ];

            if ($itemModel->save($data)) {
                // Success
                return $this->response->setJSON(['success' => true, 'message' => 'Item registered successfully!']);
            } else {
                // Save failed (e.g., model validation error)
                return $this->response->setJSON(['success' => false, 'errors' => $itemModel->errors()])
                    ->setStatusCode(500);
            }

        } catch (\Exception $e) {
            // Catch any other database errors
            log_message('error', '[ItemController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not save item. Please try again.']])
                ->setStatusCode(500);
        }
    }

    /**
     * Handle AJAX request to fetch a single item's data
     */
    public function getItem($id = null): ResponseInterface
    {
        // Check if it's an AJAX request
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Authentication required.'])
                ->setStatusCode(401);
        }

        if ($id === null) {
            return $this->response->setJSON(['success' => false, 'error' => 'No item ID provided.'])
                ->setStatusCode(400);
        }

        $itemModel = new ItemModel();
        $studentId = session()->get('student_id');

        // Find the item AND ensure it belongs to the logged-in student
        $item = $itemModel->where('id', $id)
            ->where('student_id', $studentId)
            ->first();

        if ($item) {
            return $this->response->setJSON(['success' => true, 'item' => $item]);
        }

        // Not found or doesn't belong to user
        return $this->response->setJSON(['success' => false, 'error' => 'Item not found.'])
            ->setStatusCode(404);
    }

    /**
     * Handle the item update form submission via AJAX
     */
    public function update(): ResponseInterface
    {
        // Check if it's an AJAX request
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Authentication required.'])
                ->setStatusCode(401);
        }

        $studentId = session()->get('student_id');
        $itemId = $this->request->getPost('item_id');

        // 1. Validation Rules
        $rules = [
            'item_id'       => 'required|numeric',
            'item_name'     => 'required|string|max_length[255]',
            // --- UPDATED: Use new custom rule and pass ignore parameters ---
            'serial_number' => "required|string|max_length[255]|is_unique_soft_delete[items,serial_number,id,{$itemId}]",
            'category'      => 'required|string|max_length[100]',
            'description'   => 'permit_empty|string|max_length[500]',
            'item_image' => [ // Image is OPTIONAL on update
                'label' => 'Item Image',
                'rules' => 'if_exist|uploaded[item_image]'
                    . '|is_image[item_image]'
                    . '|mime_in[item_image,image/jpg,image/jpeg,image/png,image/gif]'
                    . '|max_size[item_image,10240]', // --- UPDATED: Increased to 10MB ---
            ],
        ];

        if (! $this->validate($rules)) {
            // Return validation errors as JSON
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400);
        }

        $itemModel = new ItemModel();

        // 2. Verify ownership
        $item = $itemModel->where('id', $itemId)
            ->where('student_id', $studentId)
            ->first();

        if (! $item) {
            return $this->response->setJSON(['success' => false, 'error' => 'You do not have permission to edit this item.'])
                ->setStatusCode(403);
        }

        // 3. Handle File Upload (if new one is provided)
        $img = $this->request->getFile('item_image');
        $newImagePath = $item['image_path']; // Default to old path
        $oldImagePath = $item['image_path'];

        if ($img && $img->isValid() && ! $img->hasMoved()) {
            $newName = $img->getRandomName();
            if ($img->move(FCPATH . 'uploads/item_images', $newName)) {
                $newImagePath = $newName; // Set new path

                // Delete the old image file if it exists
                if ($oldImagePath && file_exists(FCPATH . 'uploads/item_images/' . $oldImagePath)) {
                    @unlink(FCPATH . 'uploads/item_images/' . $oldImagePath);
                }
            } else {
                // File move failed
                return $this->response->setJSON(['success' => false, 'errors' => ['item_image' => $img->getErrorString()]])
                    ->setStatusCode(500);
            }
        }

        // 4. Prepare data for update
        try {
            $data = [
                'item_name'     => $this->request->getPost('item_name'),
                'serial_number' => $this->request->getPost('serial_number'),
                'category'      => $this->request->getPost('category'),
                'description'   => $this->request->getPost('description'),
                'image_path'    => $newImagePath,
            ];

            if ($itemModel->update($itemId, $data)) {
                // Success
                return $this->response->setJSON(['success' => true, 'message' => 'Item updated successfully!']);
            } else {
                // Save failed
                return $this->response->setJSON(['success' => false, 'errors' => $itemModel->errors()])
                    ->setStatusCode(500);
            }

        } catch (\Exception $e) {
            // Catch any other database errors
            log_message('error', '[ItemController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not update item. Please try again.']])
                ->setStatusCode(500);
        }
    }


    /**
     * Handle the item removal request via AJAX
     */
    public function delete(): ResponseInterface
    {
        // Check if it's an AJAX request
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Authentication required.'])
                ->setStatusCode(401);
        }

        // 1. Validation
        $rules = [
            'item_id' => 'required|numeric',
            'reason'  => 'required|string|max_length[500]', // <-- CHANGED
        ];

        $messages = [
            'reason' => [
                'required' => 'A reason for removal is required.' // <-- ADDED
            ]
        ];

        if (! $this->validate($rules, $messages)) { // <-- UPDATED
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400);
        }

        // 2. Find and Soft Delete the item
        try {
            $itemModel = new ItemModel();
            $studentId = session()->get('student_id');
            $itemId = $this->request->getPost('item_id');
            $reason = $this->request->getPost('reason');

            // Find the item AND ensure it belongs to the logged-in student
            $item = $itemModel->where('id', $itemId)
                ->where('student_id', $studentId)
                ->first();

            if ($item) {
                // First, update the reason
                $itemModel->update($itemId, ['reason_for_removal' => $reason]);

                // Then, soft delete
                if ($itemModel->delete($itemId)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Item removed successfully.']);
                }

                return $this->response->setJSON(['success' => false, 'error' => 'Failed to remove item.'])
                    ->setStatusCode(500);
            }

            // Not found or doesn't belong to user
            return $this->response->setJSON(['success' => false, 'error' => 'Item not found.'])
                ->setStatusCode(404);

        } catch (\Exception $e) {
            log_message('error', '[ItemController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not remove item. Please try again.']])
                ->setStatusCode(500);
        }
    }

    /**
     * Handle the item report (missing/stolen) request via AJAX
     */
    public function report(): ResponseInterface
    {
        // Check if it's an AJAX request
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Authentication required.'])
                ->setStatusCode(401);
        }

        // 1. Validation
        $rules = [
            'item_id'       => 'required|numeric',
            'report_type'   => 'required|in_list[missing,stolen]',
            'report_reason' => 'required|string|max_length[500]',
        ];

        $messages = [
            'report_type' => [
                'in_list' => 'Invalid report type selected.',
            ],
            'report_reason' => [
                'required' => 'A reason for the report is required.',
            ]
        ];

        if (! $this->validate($rules, $messages)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400);
        }

        // 2. Find and Update the item
        try {
            $itemModel = new ItemModel();
            $studentId = session()->get('student_id');
            $itemId = $this->request->getPost('item_id');
            $reportType = $this->request->getPost('report_type');
            $reportReason = $this->request->getPost('report_reason');

            // Find the item AND ensure it belongs to the logged-in student
            $item = $itemModel->where('id', $itemId)
                ->where('student_id', $studentId)
                ->first();

            if (! $item) {
                // Not found or doesn't belong to user
                return $this->response->setJSON(['success' => false, 'error' => 'Item not found.'])
                    ->setStatusCode(404);
            }

            // --- CRITICAL CHECK: Must be 'inside' to be reported ---
            if ($item['status'] !== 'inside') {
                return $this->response->setJSON(['success' => false, 'error' => 'You can only report items that are currently marked as "Inside Campus".'])
                    ->setStatusCode(403); // Forbidden
            }

            // Update the item status and add the report reason
            $data = [
                'status'         => $reportType, // Set status to 'missing' or 'stolen'
                'report_details' => $reportReason
            ];

            if ($itemModel->update($itemId, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Item reported successfully.']);
            }

            return $this->response->setJSON(['success' => false, 'error' => 'Failed to report item.'])
                ->setStatusCode(500);

        } catch (\Exception $e) {
            log_message('error', '[ItemController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not report item. Please try again.']])
                ->setStatusCode(500);
        }
    }

    /**
     * --- NEW FUNCTION ---
     * Handle the item "Mark as Found" request via AJAX
     */
    public function markAsFound(): ResponseInterface
    {
        // Check if it's an AJAX request
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        // Check if student is logged in
        if (! session()->get('student_logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Authentication required.'])
                ->setStatusCode(401);
        }

        // 1. Validation
        $rules = [
            'item_id' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])
                ->setStatusCode(400);
        }

        // 2. Find and Update the item
        try {
            $itemModel = new ItemModel();
            $studentId = session()->get('student_id');
            $itemId = $this->request->getPost('item_id');

            // Find the item AND ensure it belongs to the logged-in student
            $item = $itemModel->where('id', $itemId)
                ->where('student_id', $studentId)
                ->first();

            if (! $item) {
                // Not found or doesn't belong to user
                return $this->response->setJSON(['success' => false, 'error' => 'Item not found.'])
                    ->setStatusCode(404);
            }

            // --- CRITICAL CHECK: Must be 'missing' or 'stolen' to be "found" ---
            if (!in_array($item['status'], ['missing', 'stolen'])) {
                return $this->response->setJSON(['success' => false, 'error' => 'This item is not currently reported as missing or stolen.'])
                    ->setStatusCode(403); // Forbidden
            }

            // Update the item status and clear the report reason
            $data = [
                'status'         => 'outside', // Set status to 'outside' (safe default)
                'report_details' => null       // Clear the old report
            ];

            if ($itemModel->update($itemId, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Item marked as found! Its status has been reset.']);
            }

            return $this->response->setJSON(['success' => false, 'error' => 'Failed to update item.'])
                ->setStatusCode(500);

        } catch (\Exception $e) {
            log_message('error', '[ItemController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Could not update item. Please try again.']])
                ->setStatusCode(500);
        }
    }
}