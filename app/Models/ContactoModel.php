<?php namespace App\Models;

use CodeIgniter\Model;

class ContactoModel extends Model
{
    protected $table = 'contactos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['cliente_id', 'email', 'telefono', 'nombre', 'cargo'];

    protected $useTimestamps = true;
    protected $createdField  = 'creado_en';
    protected $updatedField  = 'actualizado_en';
    protected $deletedField  = 'eliminado_en';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
