<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketMovimientoModel extends Model
{
    protected $table = 'ticket_movimientos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['ticket_id', 'tipo_ticket_id', 'descripcion', 'fecha_movimiento', 'usuario_id', 'imagen', 'media', 'tipo_movimiento', 'auto'];
    protected $useTimestamps = false;
    protected $createdField  = 'fecha_movimiento';
    protected $updatedField  = '';
    protected $deletedField  = '';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getMovimientosWithDetails($ticketId)
    {
        return $this->select('ticket_movimientos.*, tipos_ticket.nombre as tipo_movimiento_nombre, usuarios.nombre as usuario_nombre, usuarios.imagen as usuario_imagen')
                    ->join('tipos_ticket', 'tipos_ticket.id = ticket_movimientos.tipo_ticket_id', 'left')
                    ->join('usuarios', 'usuarios.id = ticket_movimientos.usuario_id', 'left')
                    ->where('ticket_movimientos.ticket_id', $ticketId)
                    ->orderBy('ticket_movimientos.fecha_movimiento', 'DESC')
                    ->findAll();
    }
}
