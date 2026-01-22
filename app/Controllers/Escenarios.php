<?php 

namespace App\Controllers;

use App\Models\EscenarioModel;
use CodeIgniter\RESTful\ResourceController;

class Escenarios extends ResourceController

{
    protected $modelName = 'App\Models\EscenarioModel';
    protected $format    = 'json';

    public function index()
    {
        $escenarios = $this->model->findAll();
        return $this->respond($escenarios);
    }

    public function getEscenariosUsuario()
    {
        $usuario_id = session()->get('id');
        $escenarioModel = new EscenarioModel();
        return $escenarioModel->getEscenariosPorUsuario($usuario_id);
    }

}

// En Escenarios.php



