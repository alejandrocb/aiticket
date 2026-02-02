<?php namespace App\Models;

use CodeIgniter\Model;

class PrioridadesTicketModel extends Model
{
    protected $table = 'prioridades_ticket';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nombre'];
    
    protected $returnType = 'array';
    protected $useTimestamps = false;


}