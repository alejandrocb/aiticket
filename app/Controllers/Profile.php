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
            'title' => 'Perfil y Ajustes',
            'usuario' => $usuario,
            'content' => 'profile/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }
}
