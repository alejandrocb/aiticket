<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\ClienteModel;
use App\Models\TiposticketModel;
use App\Models\PrioridadesTicketModel;
use App\Models\EstadosTicketModel;
use App\Models\TicketMovimientoModel;
use App\Models\UsuarioModel;
use App\Models\NotificationModel;
use App\Models\EscenarioModel;
use CodeIgniter\Controller;

class Tickets extends Controller
{
    protected $ticketModel;
    protected $clienteModel;
    protected $tiposticketModel;
    protected $prioridadesTicketModel;
    protected $estadosTicketModel;
    protected $usuarioModel;
    protected $ticketMovimientoModel;
    protected $escenarioModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->clienteModel = new ClienteModel();
        $this->tiposticketModel = new TiposticketModel();
        $this->prioridadesTicketModel = new PrioridadesTicketModel();
        $this->estadosTicketModel = new EstadosTicketModel();
        $this->usuarioModel = new UsuarioModel();
        $this->ticketMovimientoModel = new TicketMovimientoModel();
        $this->escenarioModel = new EscenarioModel();
        $this->notificationModel = new NotificationModel();
    }
    public function index()
    {
        $userId = session()->get('id');
        $roleId = session()->get('rol_id');

        $escenariosActivos = $this->getEscenariosActivos();

        // Si es admin (rol 1) o soporte (rol 2), ve todos los tickets de sus escenarios
        if ($roleId == 1 || $roleId == 2) {
             $tickets = $this->ticketModel->getTicketsWithClients();
        } else {
            // Si es cliente (rol 3), solo ve sus propios tickets
             $tickets = $this->ticketModel->getTicketsWithClients(session()->get('cliente_id'));
        }
        
        // --- Lógica Check 1: Marcar como Visto (visto_responsable_at) ---
        // Si el usuario actual es el responsable Y no lo ha visto aún
        if ($tickets) {
            $idsToMarkAsSeen = [];
            foreach ($tickets as $t) {
                if ($t['responsable_id'] == $userId && empty($t['visto_responsable_at'])) {
                    $idsToMarkAsSeen[] = $t['id'];
                }
            }
            if (!empty($idsToMarkAsSeen)) {
                $now = date('Y-m-d H:i:s');
                $this->ticketModel->whereIn('id', $idsToMarkAsSeen)
                                  ->set(['visto_responsable_at' => $now])
                                  ->update();
                
                // Actualizar en memoria para que se vea reflejado ya
                foreach ($tickets as &$t) {
                    if (in_array($t['id'], $idsToMarkAsSeen)) {
                        $t['visto_responsable_at'] = $now;
                    }
                }
            }
        }

        $data = [
            'title' => 'Listado de Tickets',
            'tickets' => $tickets,
            'content' => 'tickets/index_modern'
        ];

        return view('templates/layout_modern', $data);
    }
    public function store()
    {
        $ticketModel = new TicketModel();
        $ticketMovimientoModel = new TicketMovimientoModel();
        $this->notificationModel = new NotificationModel();

        // 1. Recoger datos del formulario
        $clienteId = $this->request->getPost('cliente_id');
        $cliente = $this->clienteModel->find($clienteId);
        
        if (!$cliente) {
            return redirect()->back()->withInput()->with('errors', 'Cliente no válido.');
        }

        $data = [
            'cliente_id'          => $clienteId,
            'responsable_id'      => $this->request->getPost('responsable_id'),
            'tipo_ticket_id'      => $this->request->getPost('tipo_ticket_id'),
            'prioridad_ticket_id' => $this->request->getPost('prioridad_ticket_id'),
            'estado_ticket_id'    => $this->request->getPost('estado_ticket_id'),
            'descripcion'         => $this->request->getPost('descripcion'),
            'usuario_id'          => session()->get('id'), // Creador del ticket
            'escenario_id'        => $cliente['escenario'] // Heredar escenario del cliente
        ];

        // 2. Validación básica
        if (empty($data['cliente_id']) || empty($data['tipo_ticket_id']) || empty($data['descripcion'])) {
            return redirect()->back()->withInput()->with('errors', 'Campos obligatorios faltantes.');
        }

        // 3. Insertar Ticket
        $ticketId = $ticketModel->insert($data);

        // 3.5 Procesar Archivos (copiado de TicketMovimientos)
        $mediaFiles = [];
        $uploadedFiles = $this->request->getFiles('media');
        
        if (!empty($uploadedFiles) && isset($uploadedFiles['media'])) {
            foreach ($uploadedFiles['media'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    // Asegurar que el directorio existe
                    if (!is_dir(FCPATH . 'upload/mv')) {
                        mkdir(FCPATH . 'upload/mv', 0777, true);
                    }
                    if (!is_dir(FCPATH . 'upload/mv/thumbnails')) {
                        mkdir(FCPATH . 'upload/mv/thumbnails', 0777, true);
                    }

                    $file->move(FCPATH . 'upload/mv', $newName);
                    
                    $fileType = $file->getClientMimeType();
                    $isImage = strpos($fileType, 'image/') === 0;
                    
                    if ($isImage) {
                        \Config\Services::image()
                            ->withFile(FCPATH . 'upload/mv/' . $newName)
                            ->fit(100, 100, 'center')
                            ->save(FCPATH . 'upload/mv/thumbnails/' . $newName);
                    }
                    
                    $mediaFiles[] = [
                        'filename' => $newName,
                        'type' => $isImage ? 'image' : 'video'
                    ];
                }
            }
        }

        if ($ticketId) {
            // 4. Crear Movimiento Inicial
            $movimiento = [
                'ticket_id'       => $ticketId,
                'tipo_movimiento' => 'Creación',
                'descripcion'     => 'Ticket creado exitosamente.',
                'auto'            => 1,
                'usuario_id'      => session()->get('id'),
                'tipo_ticket_id'  => $data['tipo_ticket_id'],
                'media'           => json_encode($mediaFiles)
            ];
            $ticketMovimientoModel->insert($movimiento);

            // 5. Notificar al Responsable (si es diferente al creador)
            if (!empty($data['responsable_id']) && $data['responsable_id'] != session()->get('id')) {
                 $this->notificationModel->insert([
                    'user_id'    => $data['responsable_id'],
                    'title'      => 'Nuevo Ticket Asignado',
                    'message'    => "Se te ha asignado el ticket #{$ticketId}.",
                    'link'       => "/tickets/detail/{$ticketId}",
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return redirect()->to('/tickets')->with('success', 'Ticket creado correctamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $ticketModel->errors());
        }
    }

    public function update($id)
    {
        $ticketModel = new TicketModel();
        $ticketMovimientoModel = new TicketMovimientoModel();
        $usuarioModel = new UsuarioModel();
        $estadoTicketModel = new EstadosTicketModel();

        // Obtener el ticket actual
        $oldTicket = $ticketModel->find($id);

        $data = [
            'cliente_id' => $this->request->getPost('cliente_id'),
            'responsable_id' => $this->request->getPost('responsable_id'),
            'tipo_ticket_id' => $this->request->getPost('tipo_ticket_id'),
            'prioridad_ticket_id' => $this->request->getPost('prioridad_ticket_id'),
            'estado_ticket_id' => $this->request->getPost('estado_ticket_id'),
            'descripcion' => $this->request->getPost('descripcion'),
            'fecha_creacion' => $this->request->getPost('fecha_ticket'),
            'fecha_inicio_publicacion' => $this->request->getPost('fecha_inicio_publicacion') ?: null,
        ];
        
        // Filtrar los campos vacíos
        $data = array_filter($data, function($value) {
            return !is_null($value) && $value !== '';
        });

        // Actualizar el ticket
        $ticketModel->update($id, $data);

        // Notificaciones
        $currentUserId = session()->get('id');
        $creatorId = $oldTicket['usuario_id'];
        $responsableId = $data['responsable_id'] ?? $oldTicket['responsable_id'];
        
        $recipients = array_unique([$creatorId, $responsableId]);
        // Filter out nulls and current user
        $recipients = array_filter($recipients, function($uid) use ($currentUserId) {
            return $uid && $uid != $currentUserId;
        });

        foreach ($recipients as $recipientId) {
            $this->notificationModel->insert([
                'user_id' => $recipientId,
                'title' => 'Ticket Actualizado',
                'message' => "El ticket #{$id} ha sido actualizado.",
                'link' => "/tickets/detail/{$id}",
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Comprobar cambios en el responsable
        if (isset($data['responsable_id']) && $data['responsable_id'] != $oldTicket['responsable_id']) {
            $oldResponsable = $usuarioModel->find($oldTicket['responsable_id']);
            $newResponsable = $usuarioModel->find($data['responsable_id']);
            $nombreOld = $oldResponsable ? $oldResponsable['nombre'] : 'Sin asignar';
            $nombreNew = $newResponsable ? $newResponsable['nombre'] : 'Sin asignar';

            $movimiento = [
                'ticket_id' => $id,
                'tipo_movimiento' => 'Cambio de Responsable',
                'descripcion' => "Cambio de responsable de {$nombreOld} a {$nombreNew}",
                'auto' => 1,
                'usuario_id' => session()->get('id')
            ];
            $ticketMovimientoModel->insert($movimiento);

            // Resetear Checks porque cambió el responsable
             $ticketModel->update($id, [
                'visto_responsable_at' => null,
                'leido_responsable_at' => null
            ]);
        }

        // Comprobar cambios en el estado
        if (isset($data['estado_ticket_id']) && $data['estado_ticket_id'] != $oldTicket['estado_ticket_id']) {
            $oldEstado = $estadoTicketModel->find($oldTicket['estado_ticket_id']);
            $newEstado = $estadoTicketModel->find($data['estado_ticket_id']);
            $movimiento = [
                'ticket_id' => $id,
                'tipo_movimiento' => 'Cambio de Estado',
                'descripcion' => "Se cambia el estado de {$oldEstado['nombre']} a {$newEstado['nombre']}",
                'auto' => 1,
                'usuario_id' => session()->get('id')
            ];
            $ticketMovimientoModel->insert($movimiento);
        }

        // Comprobar cambios en la programación
        if (isset($data['fecha_inicio_publicacion']) && $data['fecha_inicio_publicacion'] != $oldTicket['fecha_inicio_publicacion']) {
            $movimiento = [
                'ticket_id' => $id,
                'tipo_movimiento' => 'Cambio de Fecha de Publicación',
                'descripcion' => "Se cambia la fecha de publicación a {$data['fecha_inicio_publicacion']}",
                'auto' => 1,
                'usuario_id' => session()->get('id')
            ];
            $ticketMovimientoModel->insert($movimiento);
        }


        return redirect()->to('/tickets');
    }

    public function delete($id)
    {
        $this->ticketModel->delete($id);
        return redirect()->to('/tickets');
    }

    public function close($id)
    {
        $data = [
            'estado_ticket_id' => 3
        ];

        if ($this->ticketModel->update($id, $data)) {
            //$this->crearMovimiento($id, 'Cierre de ticket', $this->ticketModel->find($id)['tipo_ticket_id']);
            return redirect()->to('/tickets/')->with('success', 'Ticket cerrado con éxito.');
        } else {
            return redirect()->back()->with('errors', $this->ticketModel->errors());
        }
    }

    public function create()
    {
         $data = [
            'title' => 'Crear Ticket',
            'clientes' => $this->clienteModel->getClientes(),
            'tipos' => $this->tiposticketModel->findAll(),
            'prioridades' => $this->prioridadesTicketModel->findAll(),
            'estados' => $this->estadosTicketModel->findAll(),
            'usuarios' => $this->usuarioModel->findAll(),
            'usuario_id' => session()->get('id'),
            'content' => 'tickets/form_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function edit($id)
    {
        $userId = session()->get('id');
        // Simple find for now, permission check should be robust
        $ticket = $this->ticketModel->find($id);

        $data = [
            'title' => 'Editar Ticket',
            'ticket' => $ticket,
            'clientes' => $this->clienteModel->getClientes(),
            'tipos' => $this->tiposticketModel->findAll(),
            'prioridades' => $this->prioridadesTicketModel->findAll(),
            'estados' => $this->estadosTicketModel->findAll(),
            'usuarios' => $this->usuarioModel->findAll(),
            'usuario_id' => $userId,
            'content' => 'tickets/form_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function detail($id)
    {
        $ticket = $this->ticketModel->find($id);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('errors', 'Ticket no encontrado.');
        }

        // --- Lógica Check 2: Marcar como Leído (leido_responsable_at) ---
        // Si el usuario actual es el responsable y entra al detalle, marcamos ambos (visto y leído) por si acaso.
        if ($ticket['responsable_id'] == session()->get('id') && empty($ticket['leido_responsable_at'])) {
             $this->ticketModel->update($id, [
                // Si entra directo al detalle sin pasar por lista (link email), también cuenta como visto
                'visto_responsable_at' => $ticket['visto_responsable_at'] ?? date('Y-m-d H:i:s'),
                'leido_responsable_at' => date('Y-m-d H:i:s')
             ]);
             // Actualizamos la variable ticket local para la vista
             $ticket['leido_responsable_at'] = date('Y-m-d H:i:s');
        }

        // Obtener todos los usuarios en una sola consulta
        $usuarios = $this->usuarioModel->findAll();
        $usuariosIndexados = array_column($usuarios, null, 'id');

        // Obtener otros datos relacionados
        $cliente = $this->clienteModel->find($ticket['cliente_id']);
        $estado = $this->estadosTicketModel->find($ticket['estado_ticket_id']);
        $tipo = $this->tiposticketModel->find($ticket['tipo_ticket_id']);
        $prioridad = $this->prioridadesTicketModel->find($ticket['prioridad_ticket_id']);
        
        // Obtener el responsable del array indexado de usuarios
        $responsable = $usuariosIndexados[$ticket['responsable_id']] ?? null;

        $data = [
            'title' => 'Detalle del Ticket',
            'ticket' => $ticket,
            'cliente_nombre' => $cliente['nombre'],
            'usuario_id' => session()->get('id'),
            'usuario_nombre' => $usuariosIndexados[session()->get('id')]['nombre'] ?? 'Usuario Desconocido',
            'usuarios' => $usuarios,
            'estado_nombre' => $estado['nombre'],
            'estados' => $this->estadosTicketModel->findAll(),
            'tipo_ticket_nombre' => $tipo['nombre'],
            'tipos_ticket' => $this->tiposticketModel->findAll(),
            'prioridad_ticket_nombre' => $prioridad['nombre'],
            'responsable_nombre' => $responsable['nombre'] ?? 'No asignado',
            'responsable_imagen' => $responsable['imagen'] ?? '',
            'responsables' => $usuarios,
            'movimientos' => $this->ticketMovimientoModel->getMovimientosWithDetails($id),
            'content' => 'tickets/detail_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function history($id)
    {
        $ticket = $this->ticketModel->find($id);
        $movimientos = $this->ticketMovimientoModel->getMovimientosWithDetails($id);

        $data = [
            'title' => 'Historial del Ticket',
            'ticket' => $ticket,
            'movimientos' => $movimientos,
            'content' => 'tickets/history_modern'
        ];

        echo view('templates/layout_modern', $data);
    }

    public function crearMovimiento($ticketId, $descripcion, $tipoTicketId)
    {
        $data = [
            'ticket_id'      => $ticketId,
            'tipo_ticket_id' => $tipoTicketId,
            'descripcion'    => $descripcion,
            'usuario_id'     => session()->get('id'),
        ];

        $this->ticketMovimientoModel->insert($data);
    }

    public function crearMovimientoActualizacion($ticketId, $oldTicket, $newData)
    {
        $cambios = [];
        foreach ($newData as $key => $value) {
            if (isset($oldTicket[$key]) && $oldTicket[$key] != $value) {
                $cambios[] = "$key: " . $oldTicket[$key] . " -> $value";
            }
        }

        if (!empty($cambios)) {
            $descripcion = "Actualización del ticket: " . implode(", ", $cambios);
            $this->crearMovimiento($ticketId, $descripcion, $newData['tipo_ticket_id'] ?? $oldTicket['tipo_ticket_id']);
        }
    }

    public function actualizarYCrearMovimiento()
    {
        $ticketId = $this->request->getPost('ticket_id');
        $nuevoEstadoId = $this->request->getPost('estado_ticket_id');
        $tipoTicketId = $this->request->getPost('tipo_ticket_id');
        $descripcion = $this->request->getPost('descripcion_movimiento');

        $oldTicket = $this->ticketModel->find($ticketId);
        $data = ['estado_ticket_id' => $nuevoEstadoId];

        if ($this->ticketModel->update($ticketId, $data)) {
            $this->crearMovimiento($ticketId, $descripcion, $tipoTicketId);
            return redirect()->to("/tickets/detail/$ticketId")->with('mensaje', 'Ticket actualizado y movimiento creado con éxito');
        } else {
            return redirect()->back()->with('errors', $this->ticketModel->errors());
        }
    }

    private function usuarioTieneAccesoAEscenario($userId, $escenarioId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');
        $builder->where('usuario_id', $userId)
                ->where('escenario_id', $escenarioId)
                ->where('activo', 1);

        return $builder->countAllResults() > 0;
    }

    private function generateVideoThumbnail($videoPath, $thumbnailPath)
    {
        // Asegúrate de que FFmpeg esté instalado en tu servidor
        $ffmpeg = '/usr/bin/ffmpeg'; // Ajusta esta ruta según la ubicación de FFmpeg en tu servidor

        // Comando para generar el thumbnail
        $command = "$ffmpeg -i $videoPath -ss 00:00:01 -vframes 1 -an -vf scale=100:100 $thumbnailPath";

        // Ejecutar el comando
        exec($command, $output, $returnVar);

        // Verificar si el thumbnail se generó correctamente
        if ($returnVar !== 0 || !file_exists($thumbnailPath)) {
            log_message('error', 'Error al generar el thumbnail del video: ' . implode("\n", $output));
            return false;
        }

        return true;
    }

    private function getEscenariosActivos()
    {
        $userId = session()->get('id');

        $db = \Config\Database::connect();
        $builder = $db->table('usuario_escenario');
        $builder->select('escenario_id')
                ->where('usuario_id', $userId)
                ->where('activo', 1);
        $query = $builder->get();
        return array_column($query->getResultArray(), 'escenario_id');
    }
}