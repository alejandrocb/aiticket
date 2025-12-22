<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Desactiva la verificación de claves foráneas temporalmente
        $this->db->disableForeignKeyChecks();

        // 1. Tipos de Usuario
        $tiposUsuario = [
            [
                'id' => 1,
                'nombre' => 'Administrador',
                'descripcion' => 'Administrador'
            ]
        ];
        $this->db->table('tipos_usuario')->ignore(true)->insertBatch($tiposUsuario);

        // 2. Escenarios
        $escenarios = [
            ['id' => 1, 'nombre' => 'Alejandro Informática'],
            ['id' => 2, 'nombre' => 'GSSLZ']
        ];
        $this->db->table('escenarios')->ignore(true)->insertBatch($escenarios);

        // 3. Tipos de Ticket
        $tiposTicket = [
            ['id' => 1, 'nombre' => 'Incidencia Software', 'estilo' => null, 'icono' => 'fas fa-bug'],
            ['id' => 2, 'nombre' => 'Incidencia Hardware', 'estilo' => null, 'icono' => 'fas fa-tools'],
            ['id' => 3, 'nombre' => 'Consulta', 'estilo' => null, 'icono' => 'fas fa-info-circle'],
            ['id' => 4, 'nombre' => 'Proyecto Software', 'estilo' => null, 'icono' => 'fas fa-laptop-code'],
            ['id' => 5, 'nombre' => 'Instalación Hardware', 'estilo' => null, 'icono' => 'fas fa-hammer'],
            ['id' => 6, 'nombre' => 'Presupuesto', 'estilo' => null, 'icono' => 'fas fa-file-invoice-dollar'],
            ['id' => 7, 'nombre' => 'Correo', 'estilo' => null, 'icono' => 'fas fa-envelope'],
            ['id' => 14, 'nombre' => 'Password', 'estilo' => null, 'icono' => 'fas fa-user-lock'],
        ];
        $this->db->table('tipos_ticket')->ignore(true)->insertBatch($tiposTicket);

        // 4. Prioridades Ticket
        $prioridadesTicket = [
            ['id' => 1, 'nombre' => 'Baja', 'estilo' => '', 'icono' => 'fas fa-info-circle'],
            ['id' => 2, 'nombre' => 'Media', 'estilo' => '', 'icono' => 'fas fa-check-circle'],
            ['id' => 3, 'nombre' => 'Alta', 'estilo' => '', 'icono' => 'fas fa-exclamation-circle'],
            ['id' => 4, 'nombre' => 'Urgente', 'estilo' => '', 'icono' => 'fas fa-exclamation-triangle'],
        ];
        $this->db->table('prioridades_ticket')->ignore(true)->insertBatch($prioridadesTicket);

        // 5. Estados Ticket
        $estadosTicket = [
            ['id' => 1, 'nombre' => 'Abierto', 'estilo' => 'estado-abierto', 'icono' => 'fas fa-folder-open'],
            ['id' => 2, 'nombre' => 'Procesando', 'estilo' => 'estado-procesando', 'icono' => 'fas fa-spinner'],
            ['id' => 3, 'nombre' => 'Cerrado', 'estilo' => 'estado-cerrada', 'icono' => 'fas fa-check'],
            ['id' => 5, 'nombre' => 'Nueva', 'estilo' => 'estado-nueva', 'icono' => 'estado-cerrada'],
            ['id' => 6, 'nombre' => 'Asignada', 'estilo' => 'estado-asignada', 'icono' => 'fas fa-user-check'],
            ['id' => 7, 'nombre' => 'Pendiente de usuario', 'estilo' => 'estado-pendiente-de-usuario', 'icono' => 'fas fa-user-clock'],
            ['id' => 8, 'nombre' => 'En Curso', 'estilo' => 'estado-en-curso', 'icono' => 'fas fa-spinner'],
            ['id' => 9, 'nombre' => 'Planificada', 'estilo' => 'estado-planificada', 'icono' => 'fas fa-calendar-alt'],
            ['id' => 10, 'nombre' => 'Finalizada', 'estilo' => 'estado-finalizada', 'icono' => 'fas fa-flag-checkered'],
            ['id' => 11, 'nombre' => 'Anulada', 'estilo' => 'estado-anulada', 'icono' => 'fas fa-flag-checkered'],
        ];
        $this->db->table('estados_ticket')->ignore(true)->insertBatch($estadosTicket);

        // 6. Usuarios
        $usuarios = [
            [
                'id' => 1,
                'nombre' => 'Alejandro',
                'email' => 'taller.alejandroinformatica@gmail.com',
                'password' => '$2y$10$UO9gqclCaCoF7SwpIcSC0e5LB5e3XwPQhIG7qfHqOKak7gh38.JX6',
                'tipo_usuario_id' => 1,
                'imagen' => 'alejandro.jpg'
            ],
            [
                'id' => 7,
                'nombre' => 'Natalia',
                'email' => 'comercial.alejandroinformatica@gmail.com',
                'password' => '$2y$10$n7t2jMboNZaIQkvNh86K2OP.ZVfRSZQntlnha2M0z6JE.T/6JX93K',
                'tipo_usuario_id' => 1,
                'imagen' => 'natalia.webp'
            ],
            [
                'id' => 8,
                'nombre' => 'Alejandro (GSSLZ)',
                'email' => 'acabbet@gobiernodecanarias.org',
                'password' => '$2y$10$UO9gqclCaCoF7SwpIcSC0e5LB5e3XwPQhIG7qfHqOKak7gh38.JX6',
                'tipo_usuario_id' => 1,
                'imagen' => null
            ]
        ];
        $this->db->table('usuarios')->ignore(true)->insertBatch($usuarios);

        // 7. Usuario Escenarios
        $usuarioEscenarios = [
            ['usuario_id' => 1, 'escenario_id' => 1, 'activo' => 1],
            ['usuario_id' => 7, 'escenario_id' => 1, 'activo' => 1],
            ['usuario_id' => 8, 'escenario_id' => 2, 'activo' => 1],
        ];
        $this->db->table('usuario_escenario')->ignore(true)->insertBatch($usuarioEscenarios);

        // Reactiva la verificación de claves foráneas
        $this->db->enableForeignKeyChecks();
    }
}
