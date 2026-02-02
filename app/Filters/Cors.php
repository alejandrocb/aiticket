<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $origin = $request->getHeader('Origin');
        if ($origin) {
            $origin = $origin->getValue();
            header("Access-Control-Allow-Origin: $origin");
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                $response = service('response');
                $response->setStatusCode(200);
                $response->send();
                exit(0);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Permitir todos los orígenes, métodos y encabezados
        //$response->setHeader('Access-Control-Allow-Origin', '*');
        //$response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
        //$response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Añadir otros encabezados necesarios para autenticación
        //$response->setHeader('Access-Control-Allow-Credentials', 'true');
    }
}