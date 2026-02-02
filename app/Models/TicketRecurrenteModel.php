<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketRecurrenteModel extends Model
{
    protected $table = 'tickets_recurrentes';
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
        'responsable_id',
        'escenario_id',
        'frecuencia',
        'dia_mes',
        'dia_semana',
        'mes',
        'ultima_ejecucion'
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

    public function getTicketsRecurrentes()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        return $this->select('tickets_recurrentes.*, 
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
                      usuarios.imagen as responsable_imagen')
            ->join('clientes', 'clientes.id = tickets_recurrentes.cliente_id')
            ->join('estados_ticket', 'estados_ticket.id = tickets_recurrentes.estado_ticket_id')
            ->join('tipos_ticket', 'tipos_ticket.id = tickets_recurrentes.tipo_ticket_id')
            ->join('prioridades_ticket', 'prioridades_ticket.id = tickets_recurrentes.prioridad_ticket_id')
            ->join('usuarios', 'usuarios.id = tickets_recurrentes.responsable_id', 'left')
            ->where('estados_ticket.id <> 3')
            ->where('estados_ticket.id <> 11')
            ->whereIn('tickets_recurrentes.escenario_id', $escenariosActivos)
            ->orderBy('ultima_ejecucion', 'DESC')
            ->findAll();
    }
}

