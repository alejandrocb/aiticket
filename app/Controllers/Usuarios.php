<?php namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\TipoUsuarioModel;
use App\Models\EscenarioModel;
use CodeIgniter\RESTful\ResourceController;

class Usuarios extends ResourceController

{
    protected $modelName = 'App\Models\UsuarioModel';
    protected $format    = 'html';

    public function index()
    {
        $data = [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $this->model->select('usuarios.*, tipos_usuario.nombre as rol_nombre')
                                      ->join('tipos_usuario', 'tipos_usuario.id = usuarios.tipo_usuario_id')
                                      ->findAll(),
            'content' => 'usuarios/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function new()
    {
        $tipoUsuarioModel = new \App\Models\TipoUsuarioModel();
        
        $escenarioModel = new EscenarioModel();
        
        $data = [
            'title' => 'Crear Usuario',
            'roles' => $tipoUsuarioModel->findAll(),
            'escenarios' => $escenarioModel->findAll(),
            'content' => 'usuarios/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function create()
    {
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'tipo_usuario_id' => $this->request->getPost('tipo_usuario_id'),
        ];

        $db = \Config\Database::connect();
        $db->transStart();
        
        $userId = $this->model->insert($data);

        if ($userId) {
            $escenariosPost = $this->request->getPost('escenarios') ?? [];
            foreach ($escenariosPost as $escenarioId) {
                $db->table('usuario_escenario')->insert([
                    'usuario_id' => $userId,
                    'escenario_id' => $escenarioId,
                    'activo' => 1
                ]);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('errors', 'Error al asignar escenarios');
            }

            return redirect()->to('/usuarios')->with('success', 'Usuario creado con éxito');
        } else {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function edit($id = null)
    {
        $tipoUsuarioModel = new \App\Models\TipoUsuarioModel();
        $usuario = $this->model->find($id);

        $escenarioModel = new EscenarioModel();

        if (!$usuario) {
            return redirect()->to('/usuarios')->with('errors', 'Usuario no encontrado');
        }

        $data = [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'roles' => $tipoUsuarioModel->findAll(),
            'escenarios' => $escenarioModel->getAllWithStatus($id),
            'content' => 'usuarios/form_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function update($id = null)
    {
        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'email'    => $this->request->getPost('email'),
            'tipo_usuario_id' => $this->request->getPost('tipo_usuario_id'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        if ($this->model->update($id, $data)) {
            $escenariosPost = $this->request->getPost('escenarios') ?? [];
            
            // Sincronizar escenarios: primero desactivamos todos para este usuario
            $db->table('usuario_escenario')->where('usuario_id', $id)->update(['activo' => 0]);

            foreach ($escenariosPost as $escenarioId) {
                $check = $db->table('usuario_escenario')
                            ->where('usuario_id', $id)
                            ->where('escenario_id', $escenarioId)
                            ->get()
                            ->getRow();

                if ($check) {
                    $db->table('usuario_escenario')
                       ->where('usuario_id', $id)
                       ->where('escenario_id', $escenarioId)
                       ->update(['activo' => 1]);
                } else {
                    $db->table('usuario_escenario')->insert([
                        'usuario_id' => $id,
                        'escenario_id' => $escenarioId,
                        'activo' => 1
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('errors', 'Error al actualizar escenarios');
            }

            return redirect()->to('/usuarios')->with('success', 'Usuario actualizado con éxito');
        } else {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario eliminado con éxito');
        } else {
            return redirect()->to('/usuarios')->with('errors', 'No se pudo eliminar el usuario');
        }
    }
}

// En Usuarios.php



