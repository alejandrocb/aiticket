<?php

namespace App\Controllers;

use App\Models\TicketRecurrenteModel;
use App\Models\ClienteModel;
use App\Models\TiposticketModel;
use App\Models\PrioridadesTicketModel;
use App\Models\EstadosTicketModel;
use App\Models\UsuarioModel;
use App\Models\EscenarioModel;
use CodeIgniter\Controller;

class TicketsRecurrentes extends Controller
{
    protected $ticketRecurrenteModel;
    protected $clienteModel;
    protected $tiposticketModel;
    protected $prioridadesTicketModel;
    protected $estadosTicketModel;
    protected $usuarioModel;
    protected $escenarioModel;

    public function __construct()
    {
        $this->ticketRecurrenteModel = new TicketRecurrenteModel();
        $this->clienteModel = new ClienteModel();
        $this->tiposticketModel = new TiposticketModel();
        $this->prioridadesTicketModel = new PrioridadesTicketModel();
        $this->estadosTicketModel = new EstadosTicketModel();
        $this->usuarioModel = new UsuarioModel();
        $this->escenarioModel = new EscenarioModel();
    }

    public function index()
    {
        $escenarios = $this->escenarioModel->getEscenariosPorUsuario(session()->get('id'));

        $data = [
            'title' => 'Tickets Recurrentes',
            'tickets' => $this->ticketRecurrenteModel->getTicketsRecurrentes(),
            'escenarios' => $escenarios,
            'content' => 'recurrentes/index_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Crear Ticket Recurrente',
            'clientes' => $this->clienteModel->getClientes(),
            'tipos' => $this->tiposticketModel->findAll(),
            'prioridades' => $this->prioridadesTicketModel->findAll(),
            'estados' => $this->estadosTicketModel->findAll(),
            'usuarios' => $this->usuarioModel->getUsuariosConImagen(),
            'usuario_id' => session()->get('id'),
            'content' => 'recurrentes/form_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    // ... store ...

    public function edit($id)
    {
        $userId = session()->get('id');
        // Note: Using the model to find the recurring ticket, assuming find works or using special method
        $ticket = $this->ticketRecurrenteModel->find($id);

        if (!$ticket) {
            return redirect()->to('/recurrentes')->with('error', 'El ticket recurrente no existe.');
        }

        $data = [
            'title' => 'Editar Ticket Recurrente',
            'ticket' => $ticket,
            'clientes' => $this->clienteModel->getClientes(),
            'tipos' => $this->tiposticketModel->findAll(),
            'prioridades' => $this->prioridadesTicketModel->findAll(),
            'estados' => $this->estadosTicketModel->findAll(),
            'usuarios' => $this->usuarioModel->findAll(),
            'usuario_id' => $userId,
            'content' => 'recurrentes/form_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function delete($id)
    {
        $this->ticketModel->delete($id);
        return redirect()->to('/tickets');
    }

    private function usuarioTieneAccesoAEscenario($userId, $escenarioId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');
        $builder->where('usuario_id', $userId)
                ->where('escenario_id', $escenarioId)
                ->where('activo', 1);

        return $builder->countAllResults() > 0;
    }

}