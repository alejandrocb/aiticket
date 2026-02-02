<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Asumiendo que 'isLoggedIn' es una clave en la sesión que indica si el usuario está logueado
        if (!session()->get('isLoggedIn')) {
            // Guardar la URL actual como referente para después del login
            session()->set('redirect_url', current_url());

            // Redireccionar al login
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Método para ejecutar después del procesamiento del controlador
    }
}