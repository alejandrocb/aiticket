<?php namespace App\Models;

use CodeIgniter\Model;

class TipoUsuarioModel extends Model
{
    protected $table = 'tipos_usuario';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
