<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemLogModel extends Model
{
    protected $table            = 'item_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // --- UPDATED ---
    // Added 'guard_id'
    protected $allowedFields    = [
        'item_id',
        'guard_id',
        'log_type',
        'timestamp',
    ];

    // Dates
    protected $useTimestamps = false; // We are using a manual 'timestamp' field
}
