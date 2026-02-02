<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageToNotifications extends Migration
{
    public function up()
    {
        $this->forge->addColumn('notifications', [
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'icon'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('notifications', 'image');
    }
}
