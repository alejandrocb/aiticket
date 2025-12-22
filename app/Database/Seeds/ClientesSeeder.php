<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run()
    {
        // Desactiva la verificación de claves foráneas temporalmente
        $this->db->disableForeignKeyChecks();

        $clientes = [
            ['id' => 5, 'nombre' => 'JParrilla', 'email' => 'estefania@usuario.com', 'telefono' => '6666667', 'direccion' => 'asdf', 'escenario' => 1],
            ['id' => 6, 'nombre' => 'Comercial Marcial Pérez', 'email' => 'administracion@comercialmarcialperez.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 7, 'nombre' => 'PSL Eléctrica', 'email' => 'info@psl-electrica.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 8, 'nombre' => 'Veterinaria Timanfaya', 'email' => 'info@veterinariatimanfaya.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 9, 'nombre' => 'Jocalepe', 'email' => 'info@jocalepe.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 10, 'nombre' => 'Ferretería Playa Honda', 'email' => 'ferreteria@hotmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 11, 'nombre' => 'Jeans&Co', 'email' => 'info@jeansco.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 12, 'nombre' => 'Betancolor XXI, S.L.', 'email' => 'administracion@betancolor.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 13, 'nombre' => 'Restaurante El Risco', 'email' => 'restauranteelrisco@gmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 14, 'nombre' => 'Restaurante Dunas de Famara', 'email' => 'restaurantedunasdefamara@gmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 16, 'nombre' => 'Meromar', 'email' => 'info@meromar.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 17, 'nombre' => 'Lavandería Puerto del Carmen', 'email' => 'lavanderiapuertodelcarmen@hotmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 18, 'nombre' => 'Lavandería Arrecife', 'email' => 'lavanderiaarrecife@hotmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 22, 'nombre' => 'Dunas de Famara', 'email' => 'info@dunasdefamara.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 23, 'nombre' => 'Pablo Asador Macher', 'email' => 'pcasielles@hotmail.com', 'telefono' => '629911966', 'direccion' => null, 'escenario' => 1],
            ['id' => 28, 'nombre' => 'Parking Albareda', 'email' => 'parking@parrillacanarias.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 29, 'nombre' => 'Aloe Canteras', 'email' => 'info@aloecanteras.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 30, 'nombre' => 'Alejandro Informática', 'email' => 'taller.alejandroinformatica@gmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 31, 'nombre' => 'Contabilidad Analítica', 'email' => 'acabbet@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 32, 'nombre' => 'Gestión Presupuestaria', 'email' => 'gestionpresupuestaria@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 33, 'nombre' => 'RRHH', 'email' => 'mcarcal@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 34, 'nombre' => 'Indusphal', 'email' => 'info@indulphal.e.telefonica.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 35, 'nombre' => '5201PEDH Pediatría', 'email' => 'pediatria@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 36, 'nombre' => 'Informática', 'email' => 'informatica@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 37, 'nombre' => 'Servicios Generales', 'email' => 'serviciosgenerales@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 38, 'nombre' => 'Nahúm Cabrera', 'email' => 'nahumcabrera@gmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 39, 'nombre' => 'Crazy Loop Puerto del Carmen', 'email' => 'crazyloop@hotmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 40, 'nombre' => 'Parrilla Canarias', 'email' => 'info@parrillacanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 41, 'nombre' => 'Gestión de la Información', 'email' => 'icasherm@gobiernodecanarias.org', 'telefono' => null, 'direccion' => null, 'escenario' => 2],
            ['id' => 42, 'nombre' => 'Café Milla', 'email' => 'cafemilla@gmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 43, 'nombre' => 'Construcciones Louroconsa', 'email' => 'louroconsa.slu@gmail.com', 'telefono' => '607786470', 'direccion' => null, 'escenario' => 1],
            ['id' => 44, 'nombre' => 'Viveros amaryllis', 'email' => 'viveros85@hotmail.com', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 45, 'nombre' => 'Crazy Loop Costa teguise', 'email' => '888@hh', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
            ['id' => 47, 'nombre' => 'Zeneida Peluquera', 'email' => '888@hh', 'telefono' => null, 'direccion' => null, 'escenario' => 1],
        ];

        $this->db->table('clientes')->ignore(true)->insertBatch($clientes);
        
        // Reactiva la verificación de claves foráneas
        $this->db->enableForeignKeyChecks();
    }
}
