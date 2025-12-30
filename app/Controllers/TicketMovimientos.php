<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\TicketModel;
use App\Models\EstadosTicketModel;
use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class TicketMovimientos extends ResourceController
{
    protected $modelName = 'App\Models\TicketMovimientoModel';
    protected $format    = 'json';

    // ... existing index method ...

    public function create()
    {
        $mediaFiles = [];
        $uploadedFiles = $this->request->getFiles('media');
        
        if (!empty($uploadedFiles) && isset($uploadedFiles['media'])) {
            //Se comprueba primero si existen ficheros subidos
            foreach ($uploadedFiles['media'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
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

        $ticketId = $this->request->getPost('ticket_id');
        $ticketModel = new TicketModel();
        $ticket = $ticketModel->find($ticketId);

        if (!$ticket) {
            return redirect()->back()->with('errors', 'Ticket no encontrado');
        }

        // Logic to update Ticket if changed
        $nuevoEstado = $this->request->getPost('estado_ticket_id');
        $nuevoResponsable = $this->request->getPost('responsable_id');
        $descripcionUsuario = $this->request->getPost('descripcion');

        $updates = [];
        $changesLog = [];

        if ($nuevoEstado && $nuevoEstado != $ticket['estado_ticket_id']) {
            $updates['estado_ticket_id'] = $nuevoEstado;
            $estadoModel = new EstadosTicketModel();
            $oldSt = $estadoModel->find($ticket['estado_ticket_id']);
            $newSt = $estadoModel->find($nuevoEstado);
            $changesLog[] = "Estado cambiado de " . ($oldSt['nombre'] ?? 'N/A') . " a " . ($newSt['nombre'] ?? 'N/A');
        }

        if ($nuevoResponsable && $nuevoResponsable != $ticket['responsable_id']) {
            $updates['responsable_id'] = $nuevoResponsable;
            $usuarioModel = new UsuarioModel();
            $oldUser = $usuarioModel->find($ticket['responsable_id']);
            $newUser = $usuarioModel->find($nuevoResponsable);
            $oldName = $oldUser ? $oldUser['nombre'] : 'Sin asignar';
            $newName = $newUser ? $newUser['nombre'] : 'Sin asignar';
            $changesLog[] = "Responsable cambiado de {$oldName} a {$newName}";
        }

        if (!empty($updates)) {
            $ticketModel->update($ticketId, $updates);
            // Refresh ticket data for notification logic later
            $ticket = $ticketModel->find($ticketId);
        }

        // Validate that we have SOMETHING
        if (empty($descripcionUsuario) && empty($changesLog) && empty($mediaFiles)) {
             return redirect()->back()->with('errors', 'Debe escribir un comentario, subir un archivo o cambiar el estado/responsable.');
        }

        // Construct final description
        $finalDesc = $descripcionUsuario;
        if (!empty($changesLog)) {
            if (!empty($finalDesc)) $finalDesc .= "\n\n";
            $finalDesc .= implode("; ", $changesLog);
        }
        // Fallback title if description was empty
        if (empty($finalDesc) && !empty($changesLog)) {
             $finalDesc = "Actualización de ticket: " . implode("; ", $changesLog);
        }

        $data = [
            'ticket_id' => $ticketId,
            'tipo_movimiento' => $this->request->getPost('tipo_movimiento'),
            'descripcion' => $finalDesc,
            'usuario_id' => session()->get('id'),
            'media' => json_encode($mediaFiles)
        ];

        if ($this->model->insert($data)) {
            // Resetear checks si el que escribe NO es el responsable
            // Para que le salte la alerta "no leído" al responsable
            if ($ticket['responsable_id'] && $ticket['responsable_id'] != session()->get('id')) {
                $ticketModel->update($ticketId, [
                    'visto_responsable_at' => null,
                    'leido_responsable_at' => null
                ]);
            }

            // Notificamos
            $ticketModel = new TicketModel();
            $notificationModel = new NotificationModel();
            $ticket = $ticketModel->find($ticketId);

            if ($ticket) {
                $currentUserId = session()->get('id');
                $creatorId = $ticket['usuario_id'];
                $responsableId = $ticket['responsable_id'];
                
                $recipients = array_unique([$creatorId, $responsableId]);
                $recipients = array_filter($recipients, function($uid) use ($currentUserId) {
                    return $uid && $uid != $currentUserId;
                });

                foreach ($recipients as $recipientId) {
                    $notifTitle = 'Nuevo comentario en ticket';
                    $notifMessage = "Se ha añadido un nuevo comentario al ticket #{$ticketId}.";

                    if (!empty($changesLog)) {
                        $notifTitle = 'Actualización de Ticket';
                        $notifMessage = "El ticket #{$ticketId} ha sido actualizado: " . implode(", ", $changesLog);
                    }

                    $notificationModel->insert([
                        'user_id' => $recipientId,
                        'title' => $notifTitle,
                        'message' => $notifMessage,
                        'link' => "/tickets/detail/{$ticketId}",
                        'icon' => session()->get('imagen'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            return redirect()->to('/tickets/detail/' . $ticketId);
        } else {
            // Si hay un error, eliminamos los archivos subidos
            foreach ($mediaFiles as $file) {
                unlink(FCPATH . 'upload/mv/' . $file['filename']);
                if ($file['type'] === 'image') {
                    unlink(FCPATH . 'upload/mv/thumbnails/' . $file['filename']);
                }
            }
            return redirect()->back()->with('errors', $this->model->errors());
        }
    }


    public function show($id = null)
    {
        $movimiento = $this->model->find($id);
        if ($movimiento) {
            return $this->respond($movimiento);
        } else {
            return $this->failNotFound("No se ha encontrado un movimiento con el ID: $id");
        }
    }

    public function update($id = null)
    {
        $data = [
            'ticket_id'        => $this->request->getVar('ticket_id'),
            'tipo_movimiento'  => $this->request->getVar('tipo_movimiento'),
            'descripcion'      => $this->request->getVar('descripcion'),
            'usuario_id'       => $this->request->getVar('usuario_id'),
        ];

        if ($this->model->update($id, $data)) {
            return $this->respond($data);
        } else {
            return $this->fail($this->model->errors());
        }
    }

    public function delete($id = null)
    {
        $movimiento = $this->model->find($id);
        if ($movimiento) {
            $this->model->delete($id);
            return $this->respondDeleted($movimiento);
        } else {
            return $this->failNotFound("No se ha encontrado un movimiento con el ID: $id");
        }
    }
}
