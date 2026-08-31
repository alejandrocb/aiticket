<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Usuarios que reciben todas las notificaciones.
 *
 * Hasta ahora una notificación llegaba al creador de la incidencia y a su
 * responsable actual. En el Puesto de Mando hace falta además un usuario de
 * Dirección que reciba absolutamente todo, sin importar quién la creara ni
 * quién la tenga asignada.
 *
 * Es un flag y no un identificador único a propósito: si mañana quieren dos
 * personas en Dirección, se marcan las dos.
 */
class AddRecibeTodasNotificacionesToUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'recibe_todas_notificaciones' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'after'      => 'tipo_usuario_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', 'recibe_todas_notificaciones');
    }
}
