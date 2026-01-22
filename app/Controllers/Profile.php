<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\EscenarioModel;
use CodeIgniter\Controller;

class Profile extends Controller
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);

        if (!$usuario) {
            return redirect()->to('/login');
        }

        $escenarioModel = new EscenarioModel();
        $escenarios = $escenarioModel->getAllWithStatus($userId);

        $data = [
            'title' => 'Mi Perfil',
            'usuario' => $usuario,
            'escenarios' => $escenarios,
            'content' => 'profile/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }

    public function update()
    {
        $userId = session()->get('id');
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'email'  => $this->request->getPost('email'),
        ];

        if ($this->usuarioModel->update($userId, $data)) {
            // Actualizar sesión para que el header se vea reflejado inmediatamente
            session()->set('nombre', $data['nombre']);
            session()->set('email', $data['email']);
            return redirect()->back()->with('success', 'Perfil actualizado con éxito');
        }

        return redirect()->back()->with('error', 'No se pudo actualizar el perfil');
    }

    public function updatePassword()
    {
        $userId = session()->get('id');
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm_password');

        if (empty($password) || $password !== $confirm) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden o están vacías');
        }

        $data = [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        if ($this->usuarioModel->update($userId, $data)) {
            return redirect()->back()->with('success', 'Contraseña actualizada correctamente');
        }

        return redirect()->back()->with('error', 'Error al actualizar la contraseña');
    }

    public function updateAvatar()
    {
        $userId = session()->get('id');
        $img = $this->request->getFile('avatar');

        if ($img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'upload/avatars', $newName);

            $oldUser = $this->usuarioModel->find($userId);
            if ($oldUser['imagen'] && file_exists(FCPATH . $oldUser['imagen'])) {
                @unlink(FCPATH . $oldUser['imagen']);
            }

            $path = 'upload/avatars/' . $newName;
            $this->usuarioModel->update($userId, ['imagen' => $path]);
            
            // Actualizar sesión
            session()->set('imagen', $path);

            return redirect()->back()->with('success', 'Foto de perfil actualizada');
        }

        return redirect()->back()->with('error', 'Error al subir la imagen');
    }

    public function updateEscenarios()
    {
        $userId = session()->get('id');
        $escenariosPost = $this->request->getPost('escenarios') ?? []; // Array de IDs seleccionados

        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');

        // Empezamos transacción por seguridad
        $db->transStart();

        // Ponemos todos a activo = 0 para este usuario
        $builder->where('usuario_id', $userId)->update(['activo' => 0]);

        // Para cada ID recibido, lo activamos o lo creamos si no existe
        foreach ($escenariosPost as $escenarioId) {
            $check = $builder->where('usuario_id', $userId)
                             ->where('escenario_id', $escenarioId)
                             ->get()
                             ->getRow();

            if ($check) {
                // Existe, lo activamos
                $db->table('usuario_escenario')
                   ->where('usuario_id', $userId)
                   ->where('escenario_id', $escenarioId)
                   ->update(['activo' => 1]);
            } else {
                // No existe, lo insertamos
                $db->table('usuario_escenario')->insert([
                    'usuario_id' => $userId,
                    'escenario_id' => $escenarioId,
                    'activo' => 1
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Error al actualizar escenarios');
        }

        return redirect()->back()->with('success', 'Escenarios actualizados correctamente');
    }
}
