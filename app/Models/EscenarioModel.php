<?php

namespace App\Models;

use CodeIgniter\Model;

class EscenarioModel extends Model
{
    protected $table = 'escenarios';

    public function getEscenariosPorUsuario($usuario_id)
    {
        return $this->select('escenarios.id, escenarios.nombre, usuario_escenario.activo')
                    ->join('usuario_escenario', 'usuario_escenario.escenario_id = escenarios.id')
                    ->where('usuario_escenario.usuario_id', $usuario_id)
                    ->findAll();
    }

    public function getAllWithStatus($usuario_id)
    {
        return $this->select('escenarios.*, ue.activo')
                    ->join('usuario_escenario ue', "ue.escenario_id = escenarios.id AND ue.usuario_id = $usuario_id", 'left')
                    ->findAll();
    }
}