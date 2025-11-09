<?php

namespace App\Validation;

use Config\Database;

class CustomRules
{
    /**
     * Custom validation rule: is_unique_soft_delete
     *
     * Checks if a field is unique in the database, but only
     * for rows that have NOT been soft-deleted (deleted_at IS NULL).
     *
     * @param string|null $str    The value of the field.
     * @param string      $params The parameters [table,field,ignoreField,ignoreValue]
     * @param array       $data   All the data being validated.
     * @param string|null $error  The error message to return.
     *
     * @return bool
     */
    // --- UPDATED: Added '?' to $str and $error to fix deprecation warning ---
    public function is_unique_soft_delete(?string $str, string $params, array $data, ?string &$error = null): bool
    {
        // Default to the default database group
        $db = Database::connect();

        $p = explode(',', $params);

        $table = $p[0]; // e.g., 'items'
        $field = $p[1]; // e.g., 'serial_number'

        $builder = $db->table($table)
            ->where($field, $str)
            ->where('deleted_at IS NULL'); // This is the key logic!

        // Check for ignore parameters (for updates)
        // e.g., is_unique_soft_delete[items,serial_number,id,123]
        if (isset($p[2]) && isset($p[3])) {
            // This is an update, so ignore the row we are currently editing
            $builder->where($p[2] . ' !=', $p[3]);
        }

        $result = $builder->countAllResults() === 0;

        if (!$result) {
            $error = 'The {field} field must contain a unique value.';
        }

        return $result;
    }
}

