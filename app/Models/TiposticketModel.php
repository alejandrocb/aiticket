<?php namespace App\Models;

use CodeIgniter\Model;

class TiposticketModel extends Model
{
    protected $table = 'tipos_ticket';
    protected $primaryKey = 'id';
    
    protected $allowedFields = ['nombre'];
    
    protected $returnType = 'array';
    protected $useTimestamps = false;


}