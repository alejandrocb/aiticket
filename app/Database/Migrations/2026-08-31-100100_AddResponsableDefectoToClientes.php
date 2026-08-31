<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Responsable por defecto de cada cliente.
 *
 * En el Puesto de Mando cada grupo de acción tiene su usuario sentado en un
 * puesto, así que al dar de alta una incidencia para el Grupo Sanitario el
 * responsable inicial debe ser el usuario Grupo Sanitario. A partir de ahí la
 * incidencia va cambiando de responsable conforme pasa de puesto en puesto.
 *
 * Nullable: NULL significa "sin responsable por defecto", el comportamiento
 * anterior. La instalación de soporte no se ve afectada.
 */
class AddResponsableDefectoToClientes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clientes', [
            'responsable_defecto_id' => [
                'type'    => 'INT',
                'null'    => true,
                'default' => null,
                'after'   => 'escenario',
            ],
        ]);

        $this->db->query(
            'ALTER TABLE `clientes`
             ADD CONSTRAINT `clientes_responsable_defecto_fk`
             FOREIGN KEY (`responsable_defecto_id`) REFERENCES `usuarios` (`id`)
             ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `clientes` DROP FOREIGN KEY `clientes_responsable_defecto_fk`');
        $this->forge->dropColumn('clientes', 'responsable_defecto_id');
    }
}
