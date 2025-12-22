<?php 

namespace App\Controllers;

use App\Models\PrioridadesTicketModel;
use CodeIgniter\RESTful\ResourceController;

class PrioridadesTicket extends ResourceController

{
    protected $modelName = 'App\Models\PrioridadesTicketModel';
    protected $format    = 'html';

    public function index()
    {
        $data = [
            'title' => 'Gestión de Prioridades de Ticket',
            'items' => $this->model->findAll(),
            'resource_name' => 'prioridadesticket',
            'singular_name' => 'Prioridad de Ticket',
            'content' => 'prioridadesticket/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Crear Prioridad de Ticket',
            'resource_name' => 'prioridadesticket',
            'singular_name' => 'Prioridad de Ticket',
            'content' => 'prioridadesticket/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function create()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/prioridadesticket')->with('success', 'Prioridad creada con éxito');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function edit($id = null)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to('/prioridadesticket')->with('errors', 'Registro no encontrado');
        }

        $data = [
            'title' => 'Editar Prioridad de Ticket',
            'item' => $item,
            'resource_name' => 'prioridadesticket',
            'singular_name' => 'Prioridad de Ticket',
            'content' => 'prioridadesticket/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function update($id = null)
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
        ];

        if ($this->model->update($id, $data)) {
            return redirect()->to('/prioridadesticket')->with('success', 'Registro actualizado con éxito');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/prioridadesticket')->with('success', 'Registro eliminado con éxito');
        } else {
            return redirect()->to('/prioridadesticket')->with('errors', 'No se pudo eliminar el registro');
        }
    }
}

// En PrioridadesTicket.php



