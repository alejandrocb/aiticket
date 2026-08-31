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
 * duplicar nada, y se puede volver a lanzar tras una ejecución a medias.
 *
 * Los grupos se localizan por nombre, no por id. Siete de ellos ya existían
 * en la base con los ids 48-54 y su usuario compartía ese mismo id, pero esa
 * coincidencia deja de cumplirse en cuanto entran grupos nuevos, así que no
 * se da por supuesta en ningún sitio.
 */
class PmaDolores2026Seeder extends Seeder
{
    private const ESCENARIO = 'Dolores 2026';

    private const DIRECCION_EMAIL = 'islastelecom+direccion@gmail.com';

    /** Usuarios que cubren los dos dispositivos, 2025 y 2026. */
    private const ADMINS_MULTIESCENARIO = [1, 10];

    /**
     * Grupos de acción. `cliente_id` es el que ya tenían en la base; los que
     * llevan null son grupos nuevos y se crean desde cero.
     *
     * `sufijo` compone el correo del usuario del puesto mediante
     * subdirecciones de Gmail: todos llegan al mismo buzón pero son distintos
     * entre sí, que es lo que exige la clave única de `usuarios.email`.
     */
    private const GRUPOS = [
        ['cliente' => 48,   'usuario' => 48,   'nombre' => 'MANDO 1',            'sufijo' => 'mando1'],
        ['cliente' => 49,   'usuario' => 49,   'nombre' => 'GRUPO SANITARIO',    'sufijo' => 'gruposanitario'],
        ['cliente' => 50,   'usuario' => 50,   'nombre' => 'GRUPO INTERVENCION', 'sufijo' => 'grupointervencion'],
        ['cliente' => 51,   'usuario' => 51,   'nombre' => 'POLICIA LOCAL',      'sufijo' => 'policialocal'],
        ['cliente' => 52,   'usuario' => 52,   'nombre' => 'POLICIA CANARIA',    'sufijo' => 'policiacanaria'],
        ['cliente' => 53,   'usuario' => 53,   'nombre' => 'GUARDIA CIVIL',      'sufijo' => 'guardiacivil'],
        ['cliente' => 54,   'usuario' => 54,   'nombre' => 'PROTECCION CIVIL',   'sufijo' => 'proteccioncivil'],
        ['cliente' => 55,   'usuario' => null, 'nombre' => 'LOGISTICA 1',        'sufijo' => 'logistica1'],
        ['cliente' => 56,   'usuario' => null, 'nombre' => 'AREA',               'sufijo' => 'area'],
        ['cliente' => null, 'usuario' => null, 'nombre' => 'SEGURIDAD AUXILIAR', 'sufijo' => 'seguridadauxiliar'],
        ['cliente' => null, 'usuario' => null, 'nombre' => 'PMA',                'sufijo' => 'pma'],
    ];

    /**
     * Tipologías de incidencia por grupo, tal como las facilitó el cliente.
     * AREA no tiene lista propia: se queda con el tipo global existente.
     */
    private const TIPOLOGIAS = [
        'MANDO 1' => [
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
        'GRUPO SANITARIO' => [
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
        'GRUPO INTERVENCION' => [
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
        'POLICIA LOCAL' => [
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
        'POLICIA CANARIA' => [
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
        'GUARDIA CIVIL' => [
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
        'PROTECCION CIVIL' => [
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
        'LOGISTICA 1' => [
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
        'SEGURIDAD AUXILIAR' => [
            'Intento de acceso no autorizado',
            'Entrada, acreditación o pulsera no válida',
            'Detección de objeto prohibido',
            'Cola o aglomeración en el acceso',
            'Sector completo o posible exceso de aforo',
            'Intrusión o vulneración del perímetro',
            'Conflicto entre asistentes que requiere presencia policial',
            'Persona indispuesta que requiere asistencia sanitaria',
            'Objeto abandonado o sospechoso',
            'Otra incidencia / No clasificada',
        ],
        'PMA' => [
            'Localización o información incorrecta de una incidencia',
            'Incidencia duplicada o datos contradictorios',
            'Recurso asignado no disponible o con retraso',
            'Pérdida de contacto o fallo de comunicaciones',
            'Solicitud o redistribución de recursos',
            'Saturación de un sector, recorrido o punto sanitario',
            'Coordinación entre cuerpos o cambio de responsable',
            'Escalado de incidencia o solicitud de refuerzos',
            'Otra incidencia / No clasificada',
        ],
    ];

    public function run()
    {
        $escenarioId  = $this->asegurarEscenario();
        $rolUsuarioId = $this->asegurarRolUsuario();

        $usuariosDeGrupo = [];

        foreach (self::GRUPOS as $grupo) {
            $clienteId = $this->asegurarCliente($grupo, $escenarioId);
            $usuarioId = $this->asegurarUsuario($grupo, $rolUsuarioId);

            $this->db->table('clientes')->where('id', $clienteId)
                     ->update(['responsable_defecto_id' => $usuarioId]);

            $usuariosDeGrupo[] = $usuarioId;
            $this->cargarTipologias($grupo['nombre'], $clienteId);
        }

        // Dirección se crea al final: así los ids libres se los llevan primero
        // los puestos de los grupos.
        $direccionId = $this->asegurarDireccion($rolUsuarioId);

        $this->repartirEscenarios($escenarioId, array_merge($usuariosDeGrupo, [$direccionId]));

        echo "Seeder Dolores 2026 completado.\n";
    }

    private function asegurarEscenario(): int
    {
        $fila = $this->db->table('escenarios')->where('nombre', self::ESCENARIO)->get()->getRowArray();

        if ($fila) {
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
     * Se fuerza el id 2 a propósito: el 3 corresponde a los usuarios de tipo
     * cliente, que el formulario de tickets excluye del desplegable de
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
     * Localiza el grupo por nombre y, si no existe, lo crea.
     *
     * Se mueve al escenario del dispositivo en lugar de duplicarse porque
     * `clientes` tiene una restricción única sobre `nombre` y una copia con el
     * mismo nombre daría error. Los tickets de 2025 conservan su propio
     * `escenario_id`, así que su listado no cambia; lo único que queda vacía es
     * la pantalla de clientes de aquel escenario.
     */
    private function asegurarCliente(array $grupo, int $escenarioId): int
    {
        // Se busca por id cuando se conoce, porque los nombres reales de
        // algunos grupos ('GRUPO INTERVENCION...', 'PROTECCION CIVIL...') no
        // están confirmados y buscar por nombre podría no encontrarlos y
        // acabar creando un grupo duplicado.
        $fila = $grupo['cliente'] !== null
            ? $this->db->table('clientes')->where('id', $grupo['cliente'])->get()->getRowArray()
            : $this->db->table('clientes')->where('nombre', $grupo['nombre'])->get()->getRowArray();

        if ($fila) {
            $this->db->table('clientes')->where('id', $fila['id'])
                     ->update(['escenario' => $escenarioId]);
            return (int) $fila['id'];
        }

        $this->db->table('clientes')->insert([
            'nombre'    => $grupo['nombre'],
            'email'     => $grupo['sufijo'] . '@email.com',
            'escenario' => $escenarioId,
        ]);
        $id = (int) $this->db->insertID();
        echo "Cliente '{$grupo['nombre']}' creado (id {$id}).\n";

        return $id;
    }

    /**
     * Usuario que ocupa el puesto de un grupo.
     *
     * Busca primero por el correo definitivo y después por el nombre, que es
     * lo único estable entre la situación anterior —donde el correo era
     * simplemente 'mando1'— y la nueva. Devuelve el id, sea cual sea.
     *
     * OJO: el correo es también el identificador de acceso. Al cambiarlo,
     * estos usuarios pasan a entrar con la dirección completa.
     */
    private function asegurarUsuario(array $grupo, int $rolId): int
    {
        $email = "islastelecom+{$grupo['sufijo']}@gmail.com";

        // Primero por el correo definitivo, que es lo único fiable si el
        // seeder ya se ejecutó antes. Después por el id conocido del puesto.
        $fila = $this->db->table('usuarios')->where('email', $email)->get()->getRowArray();

        if (! $fila && $grupo['usuario'] !== null) {
            $fila = $this->db->table('usuarios')->where('id', $grupo['usuario'])->get()->getRowArray();
        }

        if ($fila) {
            $this->db->table('usuarios')->where('id', $fila['id'])->update([
                'email'           => $email,
                'tipo_usuario_id' => $rolId,
            ]);
            return (int) $fila['id'];
        }

        // Usuario nuevo: la contraseña es aleatoria e inservible, de modo que
        // la cuenta queda bloqueada hasta que se le asigne una desde la
        // pantalla de usuarios. No se deja ninguna contraseña conocida escrita
        // en el repositorio.
        $this->db->table('usuarios')->insert([
            'nombre'          => $grupo['nombre'],
            'email'           => $email,
            'password'        => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
            'tipo_usuario_id' => $rolId,
        ]);
        $id = (int) $this->db->insertID();
        echo "Usuario '{$grupo['nombre']}' creado (id {$id}) — hay que ponerle contraseña.\n";

        return $id;
    }

    /** Dirección recibe todas las notificaciones, cree quien cree la incidencia. */
    private function asegurarDireccion(int $rolId): int
    {
        $fila = $this->db->table('usuarios')->where('email', self::DIRECCION_EMAIL)->get()->getRowArray();

        if ($fila) {
            $this->db->table('usuarios')->where('id', $fila['id'])
                     ->update(['recibe_todas_notificaciones' => 1]);
            return (int) $fila['id'];
        }

        $this->db->table('usuarios')->insert([
            'nombre'                      => 'DIRECCION',
            'email'                       => self::DIRECCION_EMAIL,
            'password'                    => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
            'tipo_usuario_id'             => $rolId,
            'recibe_todas_notificaciones' => 1,
        ]);
        $id = (int) $this->db->insertID();
        echo "Usuario 'DIRECCION' creado (id {$id}) — hay que ponerle contraseña.\n";

        return $id;
    }

    /**
     * Reparto de escenarios:
     * - Administradores que cubren los dos dispositivos: 2025 y 2026 activos.
     * - Puestos de grupo y Dirección: solo 2026.
     * - Natalia (id 7) se queda sin escenario, tal como está hoy.
     */
    private function repartirEscenarios(int $escenarioId, array $usuariosDelDispositivo): void
    {
        foreach (self::ADMINS_MULTIESCENARIO as $usuarioId) {
            $this->vincular($usuarioId, $escenarioId, 1);
        }

        foreach (array_unique($usuariosDelDispositivo) as $usuarioId) {
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
    private function cargarTipologias(string $nombreGrupo, int $clienteId): void
    {
        if (! isset(self::TIPOLOGIAS[$nombreGrupo])) {
            return;
        }

        $creados = 0;

        foreach (self::TIPOLOGIAS[$nombreGrupo] as $nombre) {
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

        if ($creados > 0) {
            echo "{$nombreGrupo}: {$creados} tipologías creadas.\n";
        }
    }
}
