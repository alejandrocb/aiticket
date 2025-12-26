<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SystemController extends Controller
{
    public function runMigrations($token)
    {
        // 1. Seguridad: Verificar Token
        $validToken = env('MIGRATION_TOKEN');
        
        if (empty($validToken) || $token !== $validToken) {
            return $this->response->setStatusCode(403)->setBody('Acceso Denegado: Token inválido o no configurado.');
        }

        // 2. Ejecutar Migraciones
        $migrate = \Config\Services::migrations();

        try {
            // Ejecuta todas las nuevas migraciones
            if ($migrate->latest()) {
                return $this->response->setStatusCode(200)->setBody('Migraciones ejecutadas correctamente.');
            } else {
                return $this->response->setStatusCode(200)->setBody('No hay nuevas migraciones para ejecutar.');
            }
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setBody('Error al ejecutar migración: ' . $e->getMessage());
        }
    }
}
