<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\EscenarioModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Clientes extends Controller
{
    protected $clienteModel;
    protected $escenarioModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
        $this->escenarioModel = new EscenarioModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Lista de ' . etiqueta('clientes'),
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
            'title' => 'Crear ' . etiqueta('cliente'),
            'escenarios' => $escenarios,
            'usuarios' => $this->usuarioModel->findAll(),
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
            'escenario' => $this->request->getPost('escenario_id'),
            'responsable_defecto_id' => $this->request->getPost('responsable_defecto_id') ?: null
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
            'title' => 'Editar ' . etiqueta('cliente'),
            'cliente' => $this->clienteModel->find($id),
            'usuarios' => $this->usuarioModel->findAll(),
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
            'direccion' => $this->request->getPost('direccion'),
            'responsable_defecto_id' => $this->request->getPost('responsable_defecto_id') ?: null
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
