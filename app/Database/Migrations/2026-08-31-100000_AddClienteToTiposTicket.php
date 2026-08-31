<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Permite que cada cliente tenga su propio catálogo de tipos de incidencia.
 *
 * `cliente_id` es nullable a propósito: NULL significa "tipo global,
 * disponible para todos los clientes", que es exactamente el comportamiento
 * que había antes de esta migración. Las instalaciones existentes no cambian.
 *
 * La clave foránea usa SET NULL en lugar de CASCADE porque los tickets
 * referencian `tipo_ticket_id`: borrar un cliente no debe destruir tipos que
 * el histórico sigue usando. Si un cliente desaparece, sus tipos pasan a ser
 * globales en vez de desaparecer.
 */
class AddClienteToTiposTicket extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tipos_ticket', [
            'cliente_id' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
                'after'   => 'nombre',
            ],
        ]);

        $this->db->query(
            'ALTER TABLE `tipos_ticket`
             ADD CONSTRAINT `tipos_ticket_cliente_fk`
             FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `tipos_ticket` DROP FOREIGN KEY `tipos_ticket_cliente_fk`');
        $this->forge->dropColumn('tipos_ticket', 'cliente_id');
    }
}
