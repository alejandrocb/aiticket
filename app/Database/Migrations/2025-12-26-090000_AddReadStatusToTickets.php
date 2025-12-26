<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReadStatusToTickets extends Migration
{
    public function up()
    {
        $fields = [
            'visto_responsable_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'fecha_inicio_publicacion'
            ],
            'leido_responsable_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'visto_responsable_at'
            ],
        ];

        $this->forge->addColumn('tickets', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tickets', 'visto_responsable_at');
        $this->forge->dropColumn('tickets', 'leido_responsable_at');
    }
}
