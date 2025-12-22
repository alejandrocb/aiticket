<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\TicketMovimientoModel;
use App\Models\EscenarioModel;
use CodeIgniter\Controller;

class Home extends Controller
{
    protected $ticketModel;
    protected $ticketMovimientoModel;
    protected $escenarioModel;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->ticketMovimientoModel = new TicketMovimientoModel();
        $this->escenarioModel = new EscenarioModel();
    }

    public function index()
    {
        // Obtener los datos de los tickets del último mes (TOP 5)
        $ticketsLastMonthTop5 = $this->getTicketsByClient('month', 5);

        // Obtener los datos de los tickets y movimientos de los últimos 10 días
        $ticketsLast10Days = $this->getTicketsLast10Days();
        $movimientosLast10Days = $this->getMovimientosLast10Days();

        // Obtener los escenarios del usuario actual
        $escenarios = $this->escenarioModel->getEscenariosPorUsuario(session()->get('id'));

        $data = [
            'title' => 'Dashboard',
            'escenarios' => $escenarios,
            'ticketsLastMonthTop5' => $ticketsLastMonthTop5,
            'ticketsLast10Days' => $ticketsLast10Days,
            'movimientosLast10Days' => $movimientosLast10Days,
            'content' => 'dashboard_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    private function getTicketsByClient($period, $limit = null)
    {
        $escenariosActivos = $this->getEscenariosActivos();

        $builder = $this->ticketModel->builder();

        if ($period == 'month') {
            $builder->where('DATE(tickets.fecha_creacion) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)');
        }

        $builder->select('clientes.nombre as cliente_nombre, COUNT(tickets.id) as total_tickets')
                ->join('clientes', 'clientes.id = tickets.cliente_id')
                ->whereIn('tickets.escenario_id', $escenariosActivos)
                ->groupBy('clientes.nombre')
                ->orderBy('total_tickets', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    private function getTicketsLast10Days()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        $builder = $this->ticketModel->builder();

        $builder->select('DATE(tickets.fecha_creacion) as fecha, COUNT(tickets.id) as total_tickets')
                ->where('DATE(tickets.fecha_creacion) >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)')
                ->whereIn('tickets.escenario_id', $escenariosActivos)
                ->groupBy('fecha')
                ->orderBy('fecha', 'ASC');

        return $builder->get()->getResultArray();
    }

    private function getMovimientosLast10Days()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        $builder = $this->ticketMovimientoModel->builder();

        $builder->select('DATE(ticket_movimientos.fecha_movimiento) as fecha, COUNT(ticket_movimientos.id) as total_movimientos')
                ->join('tickets', 'tickets.id = ticket_movimientos.ticket_id') // Join with tickets to get escenario_id
                ->where('DATE(ticket_movimientos.fecha_movimiento) >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)')
                ->whereIn('tickets.escenario_id', $escenariosActivos)
                ->groupBy('fecha')
                ->orderBy('fecha', 'ASC');

        return $builder->get()->getResultArray();
    }

    private function getEscenariosActivos()
    {
        $userId = session()->get('id');

        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');
        $builder->select('escenario_id')
                ->where('usuario_id', $userId)
                ->where('activo', 1);
        $query = $builder->get();
        return array_column($query->getResultArray(), 'escenario_id');
    }
}
