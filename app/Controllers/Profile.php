<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
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

        $data = [
            'title' => 'Mi Perfil',
            'usuario' => $usuario,
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
}
