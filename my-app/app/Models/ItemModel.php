<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table            = 'items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // --- UPDATED ---
    // Enabled soft deletes to use the 'deleted_at' column
    protected $useSoftDeletes   = true;

    // --- UPDATED ---
    // Added 'report_details'
    protected $allowedFields    = [
        'student_id',
        'item_name',
        'description',
        'serial_number',
        'category',
        'image_path',
        'status',
        'reason_for_removal',
        'report_details', // <-- ENSURE THIS IS PRESENT
    ];

    // Enable created_at and updated_at timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // Required for soft deletes
}