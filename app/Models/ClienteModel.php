<?php namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $allowedFields = ['nombre', 'email', 'telefono', 'direccion', 'escenario', 'responsable_defecto_id'];

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

    public function getClientes()
    {
        $escenariosActivos = $this->getEscenariosActivos();

        if (empty($escenariosActivos)) {
            return [];
        }

        return $this->select('clientes.*')
                ->whereIn('clientes.escenario', $escenariosActivos)
                ->findAll();
    }
}