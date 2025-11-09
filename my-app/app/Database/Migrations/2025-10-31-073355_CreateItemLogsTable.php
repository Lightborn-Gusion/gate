<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([ // <-- Fixed $this->forge
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'guard_id' => [ // The guard who scanned
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Nullable if a scan can happen without a guard login
            ],
            'log_type' => [
                'type'       => 'ENUM',
                'constraint' => ['entry', 'exit'],
                'null'       => false,
            ],
            'timestamp' => [
                'type' => 'DATETIME',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true); // <-- Fixed $this->forge
        $this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE'); // <-- Fixed $this->forge
        $this->forge->addForeignKey('guard_id', 'guards', 'id', 'SET NULL', 'CASCADE'); // <-- Fixed $this->forge
        $this->forge->createTable('item_logs'); // <-- Fixed $this->forge
    }

    public function down()
    {
        $this->forge->dropTable('item_logs'); // <-- Fixed $this->forge
    }
}

