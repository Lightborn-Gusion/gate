<?php

namespace App\Models;

use CodeIgniter\Model;

class GuardModel extends Model
{
    protected $table            = 'guards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    // --- UPDATED ALLOWED FIELDS ---
    protected $allowedFields    = [
        'surname',
        'firstname',
        'middlename',
        'badge_number',
        'password_hash',
        'is_active'
    ];
    // --- END UPDATED ---

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find a guard by their badge number.
     *
     * @param string $badgeNumber
     * @return object|null
     */
    public function findByBadgeNumber(string $badgeNumber): ?object
    {
        return $this->where('badge_number', $badgeNumber)->first();
    }
}

