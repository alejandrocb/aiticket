<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $allowedFields = ['nombre', 'email', 'password', 'tipo_usuario_id', 'imagen'];
    
    protected $returnType = 'array';
    protected $useTimestamps = false;

    private function getEscenariosActivos()
    {
        $session = session();
        $userId = $session->get('id');

        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');
        $builder->select('escenario_id')
                ->where('usuario_id', $userId)
                ->where('activo', 1);
        $query = $builder->get();
        return array_column($query->getResultArray(), 'escenario_id');
    }

    public function verificarUsuario($email, $password)
    {
        $user = $this->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    public function getUsuariosConImagen()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        return $this->select('usuarios.id, usuarios.nombre, usuarios.imagen')
                    ->join('usuario_escenario', 'usuarios.id = usuario_escenario.usuario_id')
                    ->whereIn('usuario_escenario.escenario_id', $escenariosActivos)
                    ->where('usuario_escenario.activo', 1)
                    ->groupBy('usuarios.id')
                    ->findAll();
    }
}
