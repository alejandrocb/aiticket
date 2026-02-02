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
    // ... store ...

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
