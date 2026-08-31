<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Ensancha `tipos_ticket.nombre` de 50 a 255 caracteres.
 *
 * Los tipos existentes eran etiquetas cortas ("Incidencia Software"), pero las
 * tipologías del Puesto de Mando son frases completas: 21 de las que hay que
 * cargar superan los 50 caracteres, y la más larga —"Urgencia médica grave:
 * dolor torácico, dificultad respiratoria, convulsión o inconsciencia"— pasa de
 * 85. Con la columna estrecha MySQL las truncaría, o daría error en modo
 * estricto.
 *
 * No hay `down()` que reduzca la columna: hacerlo cortaría datos existentes.
 */
class WidenTipoTicketNombre extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('tipos_ticket', [
            'nombre' => [
                'name'       => 'nombre',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        // Deliberadamente vacío: volver a 50 truncaría las tipologías cargadas.
    }
}
