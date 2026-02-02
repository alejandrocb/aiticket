<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIconToNotifications extends Migration
{
    public function up()
    {
        $fields = [
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'link'
            ],
        ];
        $this->forge->addColumn('notifications', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('notifications', 'icon');
    }
}
