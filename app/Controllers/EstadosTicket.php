<?php 

namespace App\Controllers;

use App\Models\EstadosticketModel;
use CodeIgniter\RESTful\ResourceController;

class EstadosTicket extends ResourceController
{
    protected $modelName = 'App\Models\EstadosTicketModel';
    protected $format    = 'html';

    public function index()
    {
        $data = [
            'title' => 'Gestión de Estados de Ticket',
            'items' => $this->model->findAll(),
            'resource_name' => 'estadosticket',
            'singular_name' => 'Estado de Ticket',
            'content' => 'estadosticket/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Crear Estado de Ticket',
            'resource_name' => 'estadosticket',
            'singular_name' => 'Estado de Ticket',
            'content' => 'estadosticket/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function create()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/estadosticket')->with('success', 'Estado creado con éxito');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function edit($id = null)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to('/estadosticket')->with('errors', 'Registro no encontrado');
        }

        $data = [
            'title' => 'Editar Estado de Ticket',
            'item' => $item,
            'resource_name' => 'estadosticket',
            'singular_name' => 'Estado de Ticket',
            'content' => 'estadosticket/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function update($id = null)
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
        ];

        if ($this->model->update($id, $data)) {
            return redirect()->to('/estadosticket')->with('success', 'Registro actualizado con éxito');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/estadosticket')->with('success', 'Registro eliminado con éxito');
        } else {
            return redirect()->to('/estadosticket')->with('errors', 'No se pudo eliminar el registro');
        }
    }
}


// En EstadosTicket.php



