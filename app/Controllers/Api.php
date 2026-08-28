<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TicketModel;
use App\Models\ClienteModel;
use App\Models\TicketMovimientoModel;
use App\Models\UsuarioModel;

class Api extends Controller
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Auth Check via ENV
        $key = $request->getHeaderLine('X-API-KEY');
        $validKey = getenv('SIMM_API_KEY');
        
        if (empty($validKey)) {
             // Fail safe if env is not set
             $this->failUnauthorized();
             exit;
        }

        if ($key !== $validKey) {
            $this->failUnauthorized();
            exit;
        }
    }

    private function failUnauthorized() {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
        exit;
    }

    private function getJsonInput() {
        return $this->request->getJSON(true); // Return as array
    }

    // --- CLIENTES ---

    public function listClients()
    {
        $model = new ClienteModel();
        // Bypass session logic by using basic findAll if needed, or stick to model logic if we simulate session?
        // Since API is admin-level, we might want ALL clients.
        $clients = $model->findAll(); 
        return $this->response->setJSON(['status' => 'success', 'data' => $clients]);
    }

    public function createClient()
    {
        $data = $this->getJsonInput();
        $model = new ClienteModel();

        if ($model->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'id' => $model->getInsertID()]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'errors' => $model->errors()]);
        }
    }

    // --- TICKETS ---

    public function listTickets()
    {
        // We use raw model to bypass session filters if we want "ALL" tickets
        $model = new TicketModel();
        
        // Custom query to get readable names without session restriction
        $tickets = $model->select('tickets.*, clientes.nombre as cliente_nombre, estados_ticket.nombre as estado_nombre')
                         ->join('clientes', 'clientes.id = tickets.cliente_id', 'left')
                         ->join('estados_ticket', 'estados_ticket.id = tickets.estado_ticket_id', 'left')
                         ->orderBy('id', 'DESC')
                         ->findAll(100); // Limit 100 for sanity

        return $this->response->setJSON(['status' => 'success', 'data' => $tickets]);
    }

    public function createTicket()
    {
        $data = $this->getJsonInput();
        $model = new TicketModel();

        // Defaults
        if (!isset($data['fecha_creacion'])) {
            $data['fecha_creacion'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['estado_ticket_id'])) {
            $data['estado_ticket_id'] = 1; // Default: Abierto/Nuevo (check DB for real IDs)
        }

        if ($model->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'id' => $model->getInsertID()]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'errors' => $model->errors()]);
        }
    }

    public function getTicket($id)
    {
        $model = new TicketModel();
        $ticket = $model->select('tickets.*, clientes.nombre as cliente_nombre')
                        ->join('clientes', 'clientes.id = tickets.cliente_id', 'left')
                        ->where('tickets.id', $id)
                        ->first();

        if ($ticket) {
            // Get movements
            $movModel = new TicketMovimientoModel();
            $movements = $movModel->where('ticket_id', $id)->orderBy('fecha_movimiento', 'DESC')->findAll();
            $ticket['movimientos'] = $movements;
            
            return $this->response->setJSON(['status' => 'success', 'data' => $ticket]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
        }
    }

    public function updateTicket()
    {
        $data = $this->getJsonInput();
        
        if (!isset($data['id'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ticket ID is required']);
        }
        
        $id = $data['id'];
        unset($data['id']); // Remove ID from update data
        
        $model = new TicketModel();
        
        // Check if ticket exists
        $ticket = $model->find($id);
        if (!$ticket) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Ticket not found']);
        }
        
        if ($model->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Ticket updated']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'errors' => $model->errors()]);
        }
    }

    // --- MOVIMIENTOS ---

    public function addMovement()
    {
        $data = $this->getJsonInput();
        $model = new TicketMovimientoModel();

        if (!isset($data['fecha_movimiento'])) {
            $data['fecha_movimiento'] = date('Y-m-d H:i:s');
        }

        if ($model->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'id' => $model->getInsertID()]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'errors' => $model->errors()]);
        }
    }
    
    // --- METADATA (Enums) ---
    
    public function metadata() {
        $db = \Config\Database::connect();
        $estados = $db->table('estados_ticket')->get()->getResultArray();
        $tipos = $db->table('tipos_ticket')->get()->getResultArray();
        $prioridades = $db->table('prioridades_ticket')->get()->getResultArray();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'estados' => $estados,
                'tipos' => $tipos,
                'prioridades' => $prioridades
            ]
        ]);
    }
}
