<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // --- UPDATED ---
    // Added 'name' and 'school_email'
    // Removed 'backup_email'
    protected $allowedFields    = ['name', 'school_id', 'school_email', 'password_hash'];

    // Dates
    protected $useTimestamps = true; // Automatically handle created_at and updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Find a student by their school ID.
     *
     * @param string $schoolId
     * @return object|null
     */
    public function findBySchoolId(string $schoolId): ?object
    {
        return $this->where('school_id', $schoolId)->first();
    }
}
