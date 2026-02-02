<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ContactoModel;

class ContactosController extends Controller
{
    public function index()
    {
        $modelo = new ContactoModel();
        $data['contactos'] = $modelo->findAll();
        return view('lista_contactos', $data);
    }

    public function create()
    {
        $modelo = new ContactoModel();
        $data = [
            'cliente_id' => $this->request->getVar('cliente_id'),
            'email' => $this->request->getVar('email'),
            'telefono' => $this->request->getVar('telefono'),
            'nombre' => $this->request->getVar('nombre'),
            'cargo' => $this->request->getVar('cargo')
        ];

        $modelo->save($data);
        return redirect()->to('/contactos');
    }

    // Métodos update y delete según se requiera
}
