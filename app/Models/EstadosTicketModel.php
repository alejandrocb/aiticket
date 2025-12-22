<?php namespace App\Models;

use CodeIgniter\Model;

class EstadosTicketModel extends Model
{
    protected $table = 'estados_ticket';
    protected $primaryKey = 'id';
    
    protected $allowedFields = ['nombre'];
    
    protected $returnType = 'array';
    protected $useTimestamps = false;


}