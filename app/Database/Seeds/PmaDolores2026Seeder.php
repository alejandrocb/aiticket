<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Datos del dispositivo Dolores 2026.
 *
 * ATENCIÓN: este seeder es específico de la instalación del Puesto de Mando.
 * No debe ejecutarse nunca en la instalación de soporte.
 *
 *   php spark db:seed PmaDolores2026Seeder
 *
 * Es idempotente: comprueba antes de insertar, así que se puede repetir sin
 * duplicar nada. Los identificadores de cliente están escritos a mano porque
 * los grupos ya existían en la base con los ids 48-56, y los usuarios que los
 * representan comparten ese mismo id.
 */
class PmaDolores2026Seeder extends Seeder
{
    private const ESCENARIO = 'Dolores 2026';

    /** Usuarios que trabajan en los dos dispositivos, 2025 y 2026. */
    private const ADMINS_MULTIESCENARIO = [1, 10];

    /**
     * Grupos de acción: id de cliente => [nombre del usuario, sufijo del correo].
     * El usuario que representa a cada grupo reutiliza el id del cliente.
     */
    private const GRUPOS = [
        48 => ['MANDO 1',            'mando1'],
        49 => ['GRUPO SANITARIO',    'gruposanitario'],
        50 => ['GRUPO INTERVENCION', 'grupointervencion'],
        51 => ['POLICIA LOCAL',      'policialocal'],
        52 => ['POLICIA CANARIA',    'policiacanaria'],
        53 => ['GUARDIA CIVIL',      'guardiacivil'],
        54 => ['PROTECCION CIVIL',   'proteccioncivil'],
        55 => ['LOGISTICA 1',        'logistica1'],
        56 => ['AREA',               'area'],
    ];

    /**
     * Tipologías de incidencia por grupo, tal como las facilitó el cliente.
     * AREA (56) no tiene lista propia: se queda con el tipo global existente.
     */
    private const TIPOLOGIAS = [
        48 => [
            'Incidente grave o con múltiples afectados',
            'Saturación de aforo o necesidad de evacuación',
            'Afección grave al recorrido o accesos',
            'Activación de refuerzos o cambio de dispositivo',
            'Falta o reasignación de recursos',
            'Situación meteorológica adversa',
            'Coordinación extraordinaria entre cuerpos',
            'Fallo crítico de comunicaciones',
            'Otra incidencia / No clasificada',
        ],
        49 => [
            'Ampollas, rozaduras o lesiones en los pies',
            'Sobrecarga muscular, calambres o contracturas',
            'Atención preventiva, masaje o recuperación muscular',
            'Esguince, torcedura, caída o traumatismo',
            'Agotamiento, deshidratación o exceso de calor',
            'Mareo, lipotimia o hipoglucemia',
            'Herida, corte o hemorragia',
            'Intoxicación por alcohol, drogas o alimentos',
            'Urgencia médica grave: dolor torácico, dificultad respiratoria, convulsión o inconsciencia',
            'Otra incidencia / No clasificada',
        ],
        50 => [
            'Incendio de vegetación o residuos en el recorrido',
            'Incendio en vehículo, generador, caseta o instalación',
            'Rescate en sendero, desnivel o zona de difícil acceso',
            'Accidente de tráfico con atrapados',
            'Fuga de gas, combustible o riesgo eléctrico',
            'Carpa, escenario, valla o estructura inestable',
            'Acceso de emergencia o evacuación bloqueada',
            'Evacuación o aseguramiento preventivo de una zona',
            'Otra incidencia / No clasificada',
        ],
        51 => [
            'Accidente, atropello o riesgo entre vehículo y peatón',
            'Vehículo obstaculizando o estacionado en acceso de emergencia',
            'Incumplimiento de corte, desvío o zona peatonal',
            'Discusión, amenazas, acoso o conflicto entre asistentes',
            'Pelea o agresión física',
            'Persona ebria, drogada, violenta o causando molestias',
            'Aglomeración, exceso de aforo, empujones o riesgo de evacuación',
            'Menor, persona vulnerable o asistente perdido',
            'Hurto, daños o vandalismo',
            'Otra incidencia / No clasificada',
        ],
        52 => [
            'Apoyo en conflictos, amenazas o situaciones de acoso',
            'Apoyo en peleas o agresiones físicas',
            'Intervención ante persona violenta o muy alterada',
            'Refuerzo ante aglomeración, exceso de aforo o evacuación',
            'Apoyo en control de accesos y perímetros',
            'Identificación o localización de personas',
            'Protección de menores, personas vulnerables o víctimas',
            'Apoyo en detención, custodia o traslado',
            'Otra incidencia / No clasificada',
        ],
        53 => [
            'Accidente o atropello en vía interurbana',
            'Vehículo averiado, inmovilizado u obstáculo en calzada',
            'Retención, corte o desvío en carretera',
            'Peregrinos caminando por zona peligrosa o invadiendo la calzada',
            'Conducción temeraria, alcoholemia, drogas o infracción grave',
            'Hurto, robo o daños',
            'Agresión, amenazas o alteración grave del orden',
            'Armas, drogas, objeto sospechoso o riesgo para la seguridad',
            'Persona desaparecida o dispositivo de búsqueda',
            'Otra incidencia / No clasificada',
        ],
        54 => [
            'Información u orientación a peregrinos',
            'Incidencia en flujo peatonal, cruce o recorrido',
            'Primera atención y aviso al grupo sanitario',
            'Apoyo a peregrino fatigado o con movilidad reducida',
            'Persona perdida, menor o persona vulnerable',
            'Riesgo detectado en camino, descanso o zona de concentración',
            'Apoyo en perímetro, evacuación o acceso de emergencias',
            'Fallo de comunicaciones o incidencia del equipo operativo',
            'Otra incidencia / No clasificada',
        ],
        55 => [
            'Corte no establecido o acceso abierto indebidamente',
            'Señalización ausente, incorrecta o deteriorada',
            'Valla, cono o balizamiento desplazado',
            'Acceso o carril de emergencia bloqueado',
            'Recorrido peatonal o desvío obstruido',
            'Residuos, vertido o desperfecto en la calzada',
            'Fallo de iluminación o balizamiento nocturno',
            'Cambio o retraso en cierre o reapertura de vía',
            'Solicitud de material o personal logístico',
            'Otra incidencia / No clasificada',
        ],
    ];

    public function run()
    {
        $escenarioId = $this->asegurarEscenario();
        $rolUsuarioId = $this->asegurarRolUsuario();

        $this->asegurarUsuariosDeGrupo($rolUsuarioId);
        $direccionId = $this->asegurarDireccion($rolUsuarioId);

        $this->moverClientesAlEscenario($escenarioId);
        $this->asignarResponsablesPorDefecto();
        $this->repartirEscenarios($escenarioId, $direccionId);
        $this->cargarTipologias();

        echo "Seeder Dolores 2026 completado.\n";
    }

    /** Crea el escenario del dispositivo si aún no existe. */
    private function asegurarEscenario(): int
    {
        $fila = $this->db->table('escenarios')->where('nombre', self::ESCENARIO)->get()->getRowArray();

        if ($fila) {
            echo "Escenario '" . self::ESCENARIO . "' ya existía (id {$fila['id']}).\n";
            return (int) $fila['id'];
        }

        $this->db->table('escenarios')->insert(['nombre' => self::ESCENARIO]);
        $id = (int) $this->db->insertID();
        echo "Escenario '" . self::ESCENARIO . "' creado (id {$id}).\n";

        return $id;
    }

    /**
     * Rol "Usuario" para los puestos de los grupos.
     *
     * Se fuerza el id 2 a propósito: el 3 está reservado para los usuarios de
     * tipo cliente, que el formulario de tickets excluye del desplegable de
     * responsables. Si el rol cayera en el 3, los grupos dejarían de ser
     * asignables.
     */
    private function asegurarRolUsuario(): int
    {
        $fila = $this->db->table('tipos_usuario')->where('nombre', 'Usuario')->get()->getRowArray();

        if ($fila) {
            return (int) $fila['id'];
        }

        $this->db->table('tipos_usuario')->insert([
            'id'          => 2,
            'nombre'      => 'Usuario',
            'descripcion' => 'Puesto de un grupo de acción',
        ]);
        echo "Rol 'Usuario' creado (id 2).\n";

        return 2;
    }

    /**
     * Cada grupo tiene su usuario, con el mismo id que el cliente.
     *
     * Los correos usan subdirecciones de Gmail (islastelecom+algo@gmail.com):
     * todos llegan al mismo buzón pero son distintos entre sí, que es lo que
     * exige la clave única de `usuarios.email`.
     *
     * OJO: el email es también el identificador de acceso. Al cambiarlo, estos
     * usuarios pasan a entrar con la dirección completa, no con 'mando1'.
     */
    private function asegurarUsuariosDeGrupo(int $rolId): void
    {
        foreach (self::GRUPOS as $clienteId => [$nombre, $sufijo]) {
            $email = "islastelecom+{$sufijo}@gmail.com";
            $existe = $this->db->table('usuarios')->where('id', $clienteId)->get()->getRowArray();

            if ($existe) {
                $this->db->table('usuarios')->where('id', $clienteId)->update([
                    'email'           => $email,
                    'tipo_usuario_id' => $rolId,
                ]);
                continue;
            }

            // Usuario nuevo: queda sin contraseña utilizable hasta que se le
            // asigne una desde la pantalla de usuarios.
            $this->db->table('usuarios')->insert([
                'id'              => $clienteId,
                'nombre'          => $nombre,
                'email'           => $email,
                'password'        => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                'tipo_usuario_id' => $rolId,
            ]);
            echo "Usuario '{$nombre}' creado (id {$clienteId}) — hay que ponerle contraseña.\n";
        }
    }

    /** Usuario de Dirección: recibe todas las notificaciones. */
    private function asegurarDireccion(int $rolId): int
    {
        $email = 'islastelecom+direccion@gmail.com';
        $fila  = $this->db->table('usuarios')->where('email', $email)->get()->getRowArray();

        if ($fila) {
            $this->db->table('usuarios')->where('id', $fila['id'])
                     ->update(['recibe_todas_notificaciones' => 1]);
            return (int) $fila['id'];
        }

        $this->db->table('usuarios')->insert([
            'nombre'                      => 'DIRECCION',
            'email'                       => $email,
            'password'                    => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
            'tipo_usuario_id'             => $rolId,
            'recibe_todas_notificaciones' => 1,
        ]);
        $id = (int) $this->db->insertID();
        echo "Usuario 'DIRECCION' creado (id {$id}) — hay que ponerle contraseña.\n";

        return $id;
    }

    /**
     * Los grupos pasan al dispositivo de 2026.
     *
     * Se mueven en lugar de duplicarse porque `clientes` tiene una restricción
     * única sobre `nombre`. Los tickets de 2025, si los hubiera, conservan su
     * propio `escenario_id`, así que su listado no se ve afectado; lo único que
     * cambia es que la pantalla de clientes del escenario 2025 queda vacía.
     */
    private function moverClientesAlEscenario(int $escenarioId): void
    {
        $ids = array_keys(self::GRUPOS);
        $this->db->table('clientes')->whereIn('id', $ids)->update(['escenario' => $escenarioId]);
        echo count($ids) . " clientes movidos al escenario {$escenarioId}.\n";
    }

    /** El responsable inicial de cada grupo es su propio usuario. */
    private function asignarResponsablesPorDefecto(): void
    {
        foreach (array_keys(self::GRUPOS) as $clienteId) {
            $this->db->table('clientes')->where('id', $clienteId)
                     ->update(['responsable_defecto_id' => $clienteId]);
        }
        echo "Responsables por defecto asignados.\n";
    }

    /**
     * Reparto de escenarios:
     * - Administradores que cubren los dos dispositivos: 2025 y 2026 activos.
     * - Usuarios de grupo y Dirección: solo 2026.
     * - Natalia (id 7) se queda sin escenario, tal como está hoy.
     */
    private function repartirEscenarios(int $escenarioId, int $direccionId): void
    {
        foreach (self::ADMINS_MULTIESCENARIO as $usuarioId) {
            $this->vincular($usuarioId, $escenarioId, 1);
        }

        $soloDosMilVeintiseis = array_merge(array_keys(self::GRUPOS), [$direccionId]);

        foreach ($soloDosMilVeintiseis as $usuarioId) {
            $this->vincular($usuarioId, $escenarioId, 1);

            // Se desactivan sus otros escenarios en lugar de borrarlos, para
            // poder revertirlo sin perder información.
            $this->db->table('usuario_escenario')
                     ->where('usuario_id', $usuarioId)
                     ->where('escenario_id !=', $escenarioId)
                     ->update(['activo' => 0]);
        }

        echo "Escenarios repartidos.\n";
    }

    private function vincular(int $usuarioId, int $escenarioId, int $activo): void
    {
        $existe = $this->db->table('usuario_escenario')
                           ->where('usuario_id', $usuarioId)
                           ->where('escenario_id', $escenarioId)
                           ->countAllResults() > 0;

        if ($existe) {
            $this->db->table('usuario_escenario')
                     ->where('usuario_id', $usuarioId)
                     ->where('escenario_id', $escenarioId)
                     ->update(['activo' => $activo]);
            return;
        }

        $this->db->table('usuario_escenario')->insert([
            'usuario_id'   => $usuarioId,
            'escenario_id' => $escenarioId,
            'activo'       => $activo,
        ]);
    }

    /** Tipologías de incidencia, colgadas de su grupo. */
    private function cargarTipologias(): void
    {
        $creados = 0;

        foreach (self::TIPOLOGIAS as $clienteId => $nombres) {
            foreach ($nombres as $nombre) {
                $existe = $this->db->table('tipos_ticket')
                                   ->where('cliente_id', $clienteId)
                                   ->where('nombre', $nombre)
                                   ->countAllResults() > 0;

                if ($existe) {
                    continue;
                }

                $this->db->table('tipos_ticket')->insert([
                    'nombre'     => $nombre,
                    'cliente_id' => $clienteId,
                    'icono'      => 'fas fa-bug',
                ]);
                $creados++;
            }
        }

        echo "{$creados} tipologías creadas.\n";
    }
}
