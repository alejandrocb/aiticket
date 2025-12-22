<?php 

namespace App\Controllers;

use App\Models\TiposticketModel;
use CodeIgniter\RESTful\ResourceController;

class TiposTicket extends ResourceController

{
    protected $modelName = 'App\Models\TiposticketModel';
    protected $format    = 'html';

    public function index()
    {
        $data = [
            'title' => 'Gestión de Tipos de Ticket',
            'items' => $this->model->findAll(),
            'resource_name' => 'tiposticket', // Helper for views
            'singular_name' => 'Tipo de Ticket',
            'content' => 'tiposticket/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Crear Tipo de Ticket',
            'resource_name' => 'tiposticket',
            'singular_name' => 'Tipo de Ticket',
            'content' => 'tiposticket/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function create()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/tiposticket')->with('success', 'Tipo creado con éxito');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function edit($id = null)
    {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->to('/tiposticket')->with('errors', 'Registro no encontrado');
        }

        $data = [
            'title' => 'Editar Tipo de Ticket',
            'item' => $item,
            'resource_name' => 'tiposticket',
            'singular_name' => 'Tipo de Ticket',
            'content' => 'tiposticket/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function update($id = null)
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
        ];

        if ($this->model->update($id, $data)) {
            return redirect()->to('/tiposticket')->with('success', 'Registro actualizado con éxito');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/tiposticket')->with('success', 'Registro eliminado con éxito');
        } else {
            return redirect()->to('/tiposticket')->with('errors', 'No se pudo eliminar el registro');
        }
    }
}

// En TiposTicket.php



