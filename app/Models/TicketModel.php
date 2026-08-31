<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
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
