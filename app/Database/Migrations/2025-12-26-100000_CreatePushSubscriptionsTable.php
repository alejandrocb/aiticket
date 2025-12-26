<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePushSubscriptionsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'endpoint' => [
                'type' => 'TEXT', // La URL única del navegador
            ],
            'public_key' => [
                'type' => 'TEXT', // Clave p256dh
                'null' => true,
            ],
            'auth_token' => [
                'type' => 'TEXT', // Clave auth
                'null' => true,
            ],
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
        $this->forge->addForeignKey('user_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('push_subscriptions');
    }

    public function down()
    {
        $this->forge->dropTable('push_subscriptions');
    }
}
