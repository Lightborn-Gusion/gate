<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuardsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // --- UPDATED NAME FIELDS ---
            'surname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'firstname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'middlename' => [
                'type'       => 'VARCHAR',
                'constraint' => '100', // For Middle Initial or full name
                'null'       => true,
            ],
            // --- END UPDATED NAME FIELDS ---
            'badge_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
                'null'       => false,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            // --- NEW FIELD FOR APPROVAL SYSTEM ---
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0, // 0 = Pending, 1 = Active.
            ],
            // --- END NEW FIELD ---
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('guards');
    }

    public function down()
    {
        $this->forge->dropTable('guards');
    }
}

