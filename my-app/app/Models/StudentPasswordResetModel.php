<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentPasswordResetModel extends Model
{
    protected $table            = 'student_password_resets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['student_email', 'token', 'expires_at'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // No updated_at field for this table
}