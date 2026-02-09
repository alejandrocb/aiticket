<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\EscenarioModel;
use CodeIgniter\Controller;

class Clientes extends Controller
{
    protected $clienteModel;
    protected $escenarioModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
        $this->escenarioModel = new EscenarioModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Lista de Clientes',
            'clientes' => $this->clienteModel->getClientes(),
            'content' => 'clientes/index_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function create()
    {

        $userId = session()->get('id');
        $escenarios = $this->escenarioModel->getEscenariosPorUsuario(session()->get('id'));

        $data = [
            'title' => 'Crear Cliente',
            'escenarios' => $escenarios,
            'content' => 'clientes/form_modern'
        ];

        echo view('templates/layout_modern', $data);

    }

    public function store()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'escenario' => $this->request->getPost('escenario_id')
        ];

        // Validación básica
        if (empty($data['nombre'])) {
            return redirect()->back()->withInput()->with('errors', 'El nombre es obligatorio.');
        }

        if ($this->clienteModel->insert($data)) {
            return redirect()->to('/clientes')->with('success', 'Cliente creado correctamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->clienteModel->errors());
        }
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Editar Cliente',
            'cliente' => $this->clienteModel->find($id),
            'content' => 'clientes/form_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function update($id)
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion')
        ];

        $this->clienteModel->update($id, $data);
        return redirect()->to('/clientes');
    }

    public function delete($id)
    {
        $this->clienteModel->delete($id);
        return redirect()->to('/clientes');
    }
}
