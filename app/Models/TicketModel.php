<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    /**
     * Estado que da una incidencia por cerrada.
     *
     * El identificador va escrito por todo el código desde antes; aquí queda
     * al menos nombrado y en un solo sitio para lo que se escriba a partir
     * de ahora.
     */
    public const ESTADO_CERRADO = 3;

    /**
     * Estados que no cuentan como abiertos: el de cerrado y el 11, que el
     * resto del código viene excluyendo junto a él de los listados.
     */
    public const ESTADOS_NO_ABIERTOS = [3, 11];

    protected $table = 'tickets';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'cliente_id',
        'usuario_id',
        'tipo_ticket_id',
        'prioridad_ticket_id',
        'estado_ticket_id',
        'descripcion',
        'fecha_creacion',
        'responsable_id',
        'escenario_id',
        'media',
        'fecha_inicio_publicacion',
        'visto_responsable_at',
        'leido_responsable_at'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    private function getEscenariosActivos()
    {
        $session = session();
        $userId = $session->get('id');

        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');
        $builder->select('escenario_id')
                ->where('usuario_id', $userId)
                ->where('activo', 1);
        $query = $builder->get();
        return array_column($query->getResultArray(), 'escenario_id');
    }

    public function findTicket($ticketId, $userId)
    {
        // Asumimos que existe una tabla de relación usuario_escenario
        return $this->select('tickets.*, clientes.nombre as cliente_nombre, escenarios.nombre as escenario_nombre')
                    ->join('clientes', 'clientes.id = tickets.cliente_id')
                    ->join('escenarios', 'escenarios.id = clientes.escenario')
                    ->join('usuario_escenario', 'usuario_escenario.escenario_id = escenarios.id')
                    ->where('tickets.id', $ticketId)
                    ->where('usuario_escenario.usuario_id', $userId)
                    ->first();
    }

    public function getTicketsWithClients($clienteId = null)
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        if ($clienteId) {
            $this->where('tickets.cliente_id', $clienteId);
        }

            return $this->select('tickets.*, 
                      clientes.nombre as cliente_nombre, 
                      estados_ticket.nombre as estado_nombre, 
                      estados_ticket.estilo as estado_estilo, 
                      estados_ticket.icono as estado_icono, 
                      tipos_ticket.nombre as tipo_ticket_nombre, 
                      tipos_ticket.icono as tipo_ticket_icono, 
                      prioridades_ticket.nombre as prioridad_ticket_nombre, 
                      prioridades_ticket.estilo as prioridad_estilo, 
                      prioridades_ticket.icono as prioridad_icono, 
                      usuarios.nombre as responsable_nombre, 
                      usuarios.imagen as responsable_imagen,
                      COALESCE(ultimo_movimiento.fecha_movimiento, tickets.fecha_creacion) as fecha_relevante,
                      ultimo_movimiento.descripcion as ultimo_movimiento,
                      ultimo_movimiento.tipo_movimiento as ultimo_movimiento_tipo,
                      ultimo_movimiento.fecha_movimiento as fecha_ultimo_movimiento,
                      ultimo_movimiento.media as ultimo_movimiento_media,
                      tickets.visto_responsable_at,
                      tickets.leido_responsable_at')
            ->join('clientes', 'clientes.id = tickets.cliente_id')
            ->join('estados_ticket', 'estados_ticket.id = tickets.estado_ticket_id')
            ->join('tipos_ticket', 'tipos_ticket.id = tickets.tipo_ticket_id')
            ->join('prioridades_ticket', 'prioridades_ticket.id = tickets.prioridad_ticket_id')
            ->join('usuarios', 'usuarios.id = tickets.responsable_id', 'left')
            ->join('(SELECT tm.ticket_id, tm.descripcion, tm.tipo_movimiento, tm.fecha_movimiento, tm.imagen, tm.media
                     FROM ticket_movimientos tm
                     INNER JOIN (
                         SELECT ticket_id, MAX(fecha_movimiento) as max_fecha
                         FROM ticket_movimientos
                         WHERE auto IS NULL OR auto != 1
                         GROUP BY ticket_id
                     ) as latest_non_auto ON tm.ticket_id = latest_non_auto.ticket_id AND tm.fecha_movimiento = latest_non_auto.max_fecha
                     WHERE tm.auto IS NULL OR tm.auto != 1) as ultimo_movimiento', 
                    'ultimo_movimiento.ticket_id = tickets.id', 'left')
            ->where('(tickets.fecha_inicio_publicacion IS NULL OR tickets.fecha_inicio_publicacion <= NOW())')
            ->whereIn('tickets.escenario_id', $escenariosActivos)
            ->orderBy('fecha_relevante', 'DESC')
            ->findAll();
    }

    public function getTicketsClosedWithClients()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        $oneMonthAgo = date('Y-m-d H:i:s', strtotime('-1 month'));

        return $this->select('tickets.*, 
                              clientes.nombre as cliente_nombre, 
                              estados_ticket.nombre as estado_nombre, 
                              estados_ticket.estilo as estado_estilo, 
                              estados_ticket.icono as estado_icono, 
                              tipos_ticket.nombre as tipo_ticket_nombre, 
                              tipos_ticket.icono as tipo_ticket_icono, 
                              prioridades_ticket.nombre as prioridad_ticket_nombre, 
                              prioridades_ticket.estilo as prioridad_estilo, 
                              prioridades_ticket.icono as prioridad_icono, 
                              usuarios.nombre as responsable_nombre, 
                              usuarios.imagen as responsable_imagen,
                              COALESCE(ticket_movimientos.fecha_movimiento, tickets.fecha_creacion) as fecha_relevante,
                              ticket_movimientos.descripcion as ultimo_movimiento,
                              ticket_movimientos.tipo_movimiento as ultimo_movimiento_tipo,
                              ticket_movimientos.fecha_movimiento as fecha_ultimo_movimiento,
                              ticket_movimientos.media as ultimo_movimiento_media,
                              ticket_movimientos.auto as auto_ultimo_movimiento,
                              tickets.visto_responsable_at,
                              tickets.leido_responsable_at')
                    ->join('clientes', 'clientes.id = tickets.cliente_id')
                    ->join('estados_ticket', 'estados_ticket.id = tickets.estado_ticket_id')
                    ->join('tipos_ticket', 'tipos_ticket.id = tickets.tipo_ticket_id')
                    ->join('prioridades_ticket', 'prioridades_ticket.id = tickets.prioridad_ticket_id')
                    ->join('usuarios', 'usuarios.id = tickets.responsable_id', 'left')
                    ->join('(SELECT ticket_id, descripcion, tipo_movimiento, fecha_movimiento, imagen, media, auto FROM ticket_movimientos WHERE id IN (SELECT MAX(id) FROM ticket_movimientos GROUP BY ticket_id)) as ticket_movimientos', 'ticket_movimientos.ticket_id = tickets.id', 'left')
                    ->where('estados_ticket.id', 3)
                    ->where('COALESCE(ticket_movimientos.fecha_movimiento, tickets.fecha_creacion) >=', $oneMonthAgo)
                    ->whereIn('tickets.escenario_id', $escenariosActivos)
                    ->orderBy('fecha_relevante', 'DESC')
                    ->findAll();
    }


    public function getTicketsScheduledWithClients()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        return $this->select('tickets.*, 
                      clientes.nombre as cliente_nombre, 
                      estados_ticket.nombre as estado_nombre, 
                      estados_ticket.estilo as estado_estilo, 
                      estados_ticket.icono as estado_icono, 
                      tipos_ticket.nombre as tipo_ticket_nombre, 
                      tipos_ticket.icono as tipo_ticket_icono, 
                      prioridades_ticket.nombre as prioridad_ticket_nombre, 
                      prioridades_ticket.estilo as prioridad_estilo, 
                      prioridades_ticket.icono as prioridad_icono, 
                      usuarios.nombre as responsable_nombre, 
                      usuarios.imagen as responsable_imagen,
                      COALESCE(ultimo_movimiento.fecha_movimiento, tickets.fecha_creacion) as fecha_relevante,
                      ultimo_movimiento.descripcion as ultimo_movimiento,
                      ultimo_movimiento.tipo_movimiento as ultimo_movimiento_tipo,
                      ultimo_movimiento.fecha_movimiento as fecha_ultimo_movimiento,
                      ultimo_movimiento.media as ultimo_movimiento_media')
            ->join('clientes', 'clientes.id = tickets.cliente_id')
            ->join('estados_ticket', 'estados_ticket.id = tickets.estado_ticket_id')
            ->join('tipos_ticket', 'tipos_ticket.id = tickets.tipo_ticket_id')
            ->join('prioridades_ticket', 'prioridades_ticket.id = tickets.prioridad_ticket_id')
            ->join('usuarios', 'usuarios.id = tickets.responsable_id', 'left')
            ->join('(SELECT tm.ticket_id, tm.descripcion, tm.tipo_movimiento, tm.fecha_movimiento, tm.imagen, tm.media
                     FROM ticket_movimientos tm
                     INNER JOIN (
                         SELECT ticket_id, MAX(fecha_movimiento) as max_fecha
                         FROM ticket_movimientos
                         WHERE auto IS NULL OR auto != 1
                         GROUP BY ticket_id
                     ) as latest_non_auto ON tm.ticket_id = latest_non_auto.ticket_id AND tm.fecha_movimiento = latest_non_auto.max_fecha
                     WHERE tm.auto IS NULL OR tm.auto != 1) as ultimo_movimiento', 
                    'ultimo_movimiento.ticket_id = tickets.id', 'left')
            ->where('estados_ticket.id <> 3')
            ->where('estados_ticket.id <> 11')
            ->where('tickets.fecha_inicio_publicacion > NOW()')
            ->whereIn('tickets.escenario_id', $escenariosActivos)
            ->orderBy('fecha_relevante', 'DESC')
            ->findAll();
    }

    /**
     * Huella del estado actual del listado, para detectar cambios sin traerse
     * los datos.
     *
     * El panel del Puesto de Mando se queda abierto durante todo el
     * dispositivo, así que sondea esta huella cada pocos segundos y solo se
     * recarga cuando cambia de verdad. Son dos consultas de agregados sobre
     * las incidencias del escenario activo: con las decenas de filas que
     * maneja un dispositivo, el coste es despreciable.
     *
     * Recoge altas y bajas (máximo id y total), movimientos nuevos (máximo id
     * de movimiento) y cambios de estado o de responsable (las sumas). Las
     * sumas podrían en teoría no variar si dos cambios se compensasen
     * exactamente entre sí; con este volumen no es un riesgo real, y el
     * siguiente movimiento lo delataría igualmente.
     */
    public function firmaDeCambios(): string
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return 'sin-escenario';
        }

        $db = \Config\Database::connect();

        $t = $db->table('tickets')
                ->select('COALESCE(MAX(id), 0) AS max_id, COUNT(id) AS total,
                          COALESCE(SUM(estado_ticket_id), 0) AS suma_estados,
                          COALESCE(SUM(responsable_id), 0) AS suma_responsables', false)
                ->whereIn('escenario_id', $escenariosActivos)
                ->get()->getRowArray();

        $m = $db->table('ticket_movimientos tm')
                ->select('COALESCE(MAX(tm.id), 0) AS max_mov', false)
                ->join('tickets t', 't.id = tm.ticket_id')
                ->whereIn('t.escenario_id', $escenariosActivos)
                ->get()->getRowArray();

        return implode('.', [
            $t['max_id'], $t['total'], $t['suma_estados'], $t['suma_responsables'], $m['max_mov'],
        ]);
    }

    // ------------------------------------------------------------------
    //  Informes
    // ------------------------------------------------------------------

    /**
     * Prepara un constructor de consultas sobre `tickets` ya acotado al
     * escenario activo y al rango de fechas del informe.
     *
     * Devuelve null si el usuario no tiene ningún escenario activo, en cuyo
     * caso no hay nada que contar y quien llama debe devolver vacío.
     */
    private function baseInforme(?string $desde, ?string $hasta)
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return null;
        }

        $builder = \Config\Database::connect()->table('tickets t')
                                              ->whereIn('t.escenario_id', $escenariosActivos);

        if ($desde) {
            $builder->where('t.fecha_creacion >=', $desde . ' 00:00:00');
        }

        if ($hasta) {
            $builder->where('t.fecha_creacion <=', $hasta . ' 23:59:59');
        }

        return $builder;
    }

    /**
     * Una incidencia con todos sus datos legibles, para el informe individual.
     *
     * A diferencia de `find()`, esta consulta **sí** filtra por escenario
     * activo: el informe se abre por su identificador en la URL y sin este
     * filtro cualquier usuario podría leer incidencias de otro dispositivo
     * cambiando el número a mano.
     *
     * Devuelve null si la incidencia no existe o no pertenece a un escenario
     * activo del usuario.
     */
    public function informeDeIncidencia($id): ?array
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return null;
        }

        $fila = $this->select('tickets.*,
                               clientes.nombre AS cliente_nombre,
                               estados_ticket.nombre AS estado_nombre,
                               tipos_ticket.nombre AS tipo_ticket_nombre,
                               prioridades_ticket.nombre AS prioridad_nombre,
                               responsable.nombre AS responsable_nombre,
                               creador.nombre AS creador_nombre,
                               escenarios.nombre AS escenario_nombre')
                    ->join('clientes', 'clientes.id = tickets.cliente_id', 'left')
                    ->join('estados_ticket', 'estados_ticket.id = tickets.estado_ticket_id', 'left')
                    ->join('tipos_ticket', 'tipos_ticket.id = tickets.tipo_ticket_id', 'left')
                    ->join('prioridades_ticket', 'prioridades_ticket.id = tickets.prioridad_ticket_id', 'left')
                    ->join('usuarios responsable', 'responsable.id = tickets.responsable_id', 'left')
                    ->join('usuarios creador', 'creador.id = tickets.usuario_id', 'left')
                    ->join('escenarios', 'escenarios.id = tickets.escenario_id', 'left')
                    ->where('tickets.id', $id)
                    ->whereIn('tickets.escenario_id', $escenariosActivos)
                    ->first();

        return $fila ?: null;
    }

    /**
     * Cifras de cabecera del informe.
     *
     * El tiempo medio de cierre es aproximado: no existe columna con la fecha
     * de cierre, así que se toma la del último movimiento de la incidencia
     * como momento en que se cerró. Es el mismo criterio que ya usa el listado
     * de cerradas. Si una incidencia se cierra y después recibe un comentario,
     * su tiempo sale algo inflado.
     */
    public function resumenGeneral(?string $desde = null, ?string $hasta = null): array
    {
        $vacio = ['total' => 0, 'abiertas' => 0, 'cerradas' => 0, 'minutos_medios' => null];

        if (! $builder = $this->baseInforme($desde, $hasta)) {
            return $vacio;
        }

        $noAbiertos = implode(',', self::ESTADOS_NO_ABIERTOS);

        $fila = $builder->select(
            'COUNT(t.id) AS total,
             SUM(CASE WHEN t.estado_ticket_id NOT IN (' . $noAbiertos . ') THEN 1 ELSE 0 END) AS abiertas,
             SUM(CASE WHEN t.estado_ticket_id = ' . self::ESTADO_CERRADO . ' THEN 1 ELSE 0 END) AS cerradas,
             AVG(CASE WHEN t.estado_ticket_id = ' . self::ESTADO_CERRADO . '
                      THEN TIMESTAMPDIFF(MINUTE, t.fecha_creacion,
                           COALESCE((SELECT MAX(m.fecha_movimiento) FROM ticket_movimientos m
                                     WHERE m.ticket_id = t.id), t.fecha_creacion))
                 END) AS minutos_medios',
            false
        )->get()->getRowArray();

        return [
            'total'          => (int) ($fila['total'] ?? 0),
            'abiertas'       => (int) ($fila['abiertas'] ?? 0),
            'cerradas'       => (int) ($fila['cerradas'] ?? 0),
            'minutos_medios' => $fila['minutos_medios'] !== null ? (int) round($fila['minutos_medios']) : null,
        ];
    }

    /**
     * Incidencias por grupo de acción, con su reparto entre abiertas y
     * cerradas.
     *
     * La consulta parte de `clientes` y no de `tickets`, con una unión por la
     * izquierda, para que **los grupos sin ninguna incidencia también salgan,
     * con un cero**. En el balance de un dispositivo, saber que un grupo no
     * reportó nada es tan informativo como saber cuánto reportó otro; si se
     * partiera de los tickets, esos grupos desaparecerían del informe.
     *
     * Por eso el filtro de fechas va en la condición de la unión y no en el
     * WHERE: puesto en el WHERE descartaría las filas sin incidencias, que es
     * justo lo que se quiere conservar.
     */
    public function resumenPorGrupo(?string $desde = null, ?string $hasta = null): array
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        $db         = \Config\Database::connect();
        $noAbiertos = implode(',', self::ESTADOS_NO_ABIERTOS);
        $escenarios = implode(',', array_map('intval', $escenariosActivos));

        // El escenario se comprueba también sobre el ticket: al mover un grupo
        // de escenario, sus incidencias antiguas conservan el suyo.
        $condicion = 't.cliente_id = c.id AND t.escenario_id IN (' . $escenarios . ')';

        if ($desde) {
            $condicion .= ' AND t.fecha_creacion >= ' . $db->escape($desde . ' 00:00:00');
        }

        if ($hasta) {
            $condicion .= ' AND t.fecha_creacion <= ' . $db->escape($hasta . ' 23:59:59');
        }

        return $db->table('clientes c')
                  ->select(
                      'c.id AS cliente_id, c.nombre AS grupo,
                       COUNT(t.id) AS total,
                       SUM(CASE WHEN t.estado_ticket_id NOT IN (' . $noAbiertos . ') THEN 1 ELSE 0 END) AS abiertas,
                       SUM(CASE WHEN t.estado_ticket_id = ' . self::ESTADO_CERRADO . ' THEN 1 ELSE 0 END) AS cerradas',
                      false
                  )
                  ->join('tickets t', $condicion, 'left')
                  ->whereIn('c.escenario', $escenariosActivos)
                  ->groupBy('c.id, c.nombre')
                  ->orderBy('total', 'DESC')
                  ->orderBy('c.nombre', 'ASC')
                  ->get()->getResultArray();
    }

    /**
     * Desglose por tipología dentro de cada grupo: el nivel de detalle que
     * responde a "qué ha pasado" y no solo a "cuántas".
     */
    public function resumenPorTipologia(?string $desde = null, ?string $hasta = null): array
    {
        if (! $builder = $this->baseInforme($desde, $hasta)) {
            return [];
        }

        $noAbiertos = implode(',', self::ESTADOS_NO_ABIERTOS);

        return $builder->select(
            'c.id AS cliente_id, c.nombre AS grupo, tt.nombre AS tipologia,
             COUNT(t.id) AS total,
             SUM(CASE WHEN t.estado_ticket_id NOT IN (' . $noAbiertos . ') THEN 1 ELSE 0 END) AS abiertas,
             SUM(CASE WHEN t.estado_ticket_id = ' . self::ESTADO_CERRADO . ' THEN 1 ELSE 0 END) AS cerradas',
            false
        )->join('clientes c', 'c.id = t.cliente_id')
         ->join('tipos_ticket tt', 'tt.id = t.tipo_ticket_id', 'left')
         ->groupBy('c.id, c.nombre, tt.nombre')
         ->orderBy('c.nombre', 'ASC')
         ->orderBy('total', 'DESC')
         ->get()->getResultArray();
    }

    /**
     * Evolución del dispositivo a lo largo del tiempo.
     *
     * Para un rango corto —un dispositivo dura dos o tres días— agrupar por
     * día daría tres barras y no diría nada; se agrupa por hora, que es donde
     * se ve la punta de trabajo. Para rangos largos, por día.
     */
    public function evolucion(?string $desde = null, ?string $hasta = null): array
    {
        if (! $builder = $this->baseInforme($desde, $hasta)) {
            return ['agrupacion' => 'hora', 'filas' => []];
        }

        $porHoras = true;

        if ($desde && $hasta) {
            $dias = (strtotime($hasta) - strtotime($desde)) / 86400;
            $porHoras = $dias <= 3;
        } elseif (! $desde && ! $hasta) {
            // Sin rango no sabemos cuánto abarca: por día es lo prudente.
            $porHoras = false;
        }

        $formato = $porHoras ? '%Y-%m-%d %H:00' : '%Y-%m-%d';

        $filas = $builder->select("DATE_FORMAT(t.fecha_creacion, '{$formato}') AS momento, COUNT(t.id) AS total", false)
                         ->groupBy('momento')
                         ->orderBy('momento', 'ASC')
                         ->get()->getResultArray();

        return ['agrupacion' => $porHoras ? 'hora' : 'dia', 'filas' => $filas];
    }

    public function getTicketsCount($status = 'abiertos')
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return 0;
        }

        $this->whereIn('tickets.escenario_id', $escenariosActivos);

        switch ($status) {
            case 'abiertos':
                $this->where('estado_ticket_id NOT IN (3,11)')
                     ->where('(tickets.fecha_inicio_publicacion IS NULL OR tickets.fecha_inicio_publicacion <= NOW())');
                break;
            case 'programados':
                $this->where('estado_ticket_id NOT IN (3,11)')
                     ->where('tickets.fecha_inicio_publicacion > NOW()');
                break;
        }

        return $this->countAllResults();
    }
}
