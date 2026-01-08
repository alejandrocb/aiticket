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
        $ticketId = $this->request->getPost('ticket_id');
        $ticketModel = new TicketModel();
        $ticket = $ticketModel->find($ticketId);

        if (!$ticket) {
            return redirect()->back()->with('errors', 'Ticket no encontrado');
        }

        $uploadedFiles = $this->request->getFiles();
        
        if (!empty($uploadedFiles) && isset($uploadedFiles['media'])) {
            $ticketDir = FCPATH . 'upload/tickets/' . $ticketId;
            $thumbDir = $ticketDir . '/thumbnails';

            foreach ($uploadedFiles['media'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    
                    if (!is_dir($ticketDir)) mkdir($ticketDir, 0777, true);
                    if (!is_dir($thumbDir)) mkdir($thumbDir, 0777, true);

                    $file->move($ticketDir, $newName);
                    
                    $fileType = $file->getClientMimeType();
                    $isImage = strpos($fileType, 'image/') === 0;
                    
                    if ($isImage) {
                        try {
                            \Config\Services::image()
                                ->withFile($ticketDir . '/' . $newName)
                                ->fit(100, 100, 'center')
                                ->save($thumbDir . '/' . $newName);
                        } catch (\Exception $e) {
                            log_message('error', 'Fallo al generar thumbnail en movimiento: ' . $e->getMessage());
                        }
                    }
                    
                    $mediaFiles[] = [
                        'filename' => $ticketId . '/' . $newName,
                        'type' => $isImage ? 'image' : 'video'
                    ];
                }
            }
        }

        $nuevoEstado = $this->request->getPost('estado_ticket_id');
        $nuevoResponsable = $this->request->getPost('responsable_id');
        $descripcionUsuario = $this->request->getPost('descripcion');
        $currentUserId = session()->get('id');

        $hasChanges = false;
        $shouldRedirectToList = false;

        // 1. Manejar Cambio de Estado
        if ($nuevoEstado && $nuevoEstado != $ticket['estado_ticket_id']) {
            $estadoModel = new EstadosTicketModel();
            $oldSt = $estadoModel->find($ticket['estado_ticket_id']);
            $newSt = $estadoModel->find($nuevoEstado);
            $descEstado = "Estado cambiado de " . ($oldSt['nombre'] ?? 'N/A') . " a " . ($newSt['nombre'] ?? 'N/A');
            
            $this->model->insert([
                'ticket_id' => $ticketId,
                'tipo_movimiento' => 'Cambio de Estado',
                'descripcion' => $descEstado,
                'usuario_id' => $currentUserId,
                'auto' => 1
            ]);
            
            $ticketModel->update($ticketId, ['estado_ticket_id' => $nuevoEstado]);
            $hasChanges = true;
            $shouldRedirectToList = true;
        }

        // 2. Manejar Cambio de Responsable
        if ($nuevoResponsable && $nuevoResponsable != $ticket['responsable_id']) {
            $usuarioModel = new UsuarioModel();
            $oldUser = $usuarioModel->find($ticket['responsable_id']);
            $newUser = $usuarioModel->find($nuevoResponsable);
            $oldName = $oldUser ? $oldUser['nombre'] : 'Sin asignar';
            $newName = $newUser ? $newUser['nombre'] : 'Sin asignar';
            $descResp = "Responsable cambiado de {$oldName} a {$newName}";

            $this->model->insert([
                'ticket_id' => $ticketId,
                'tipo_movimiento' => 'Cambio de Responsable',
                'descripcion' => $descResp,
                'usuario_id' => $currentUserId,
                'auto' => 1
            ]);

            $ticketModel->update($ticketId, [
                'responsable_id' => $nuevoResponsable,
                'visto_responsable_at' => null,
                'leido_responsable_at' => null
            ]);
            $hasChanges = true;
            $shouldRedirectToList = true;
        }

        // 3. Manejar Comentario / Media
        if (!empty($descripcionUsuario) || !empty($mediaFiles)) {
            $dataComentario = [
                'ticket_id' => $ticketId,
                'tipo_movimiento' => 'Comentario',
                'descripcion' => $descripcionUsuario ?: 'Se ha añadido contenido multimedia',
                'usuario_id' => $currentUserId,
                'media' => json_encode($mediaFiles)
            ];
            
            $this->model->insert($dataComentario);
            $hasChanges = true;

            // Resetear checks si el que escribe NO es el responsable
            if ($ticket['responsable_id'] && $ticket['responsable_id'] != $currentUserId) {
                $ticketModel->update($ticketId, [
                    'visto_responsable_at' => null,
                    'leido_responsable_at' => null
                ]);
            }
        }

        if (!$hasChanges) {
            return redirect()->back()->with('errors', 'Debe escribir un comentario, subir un archivo o cambiar el estado/responsable.');
        }

        // Notificaciones (Lógica simplificada para los cambios realizados)
        $notificationModel = new NotificationModel();
        $ticket = $ticketModel->find($ticketId); // Refresh data
        $recipients = array_unique([$ticket['usuario_id'], $ticket['responsable_id']]);
        $recipients = array_filter($recipients, function($uid) use ($currentUserId) {
            return $uid && $uid != $currentUserId;
        });

        foreach ($recipients as $recipientId) {
            $notificationModel->insert([
                'user_id' => $recipientId,
                'title' => 'Actualización de Ticket',
                'message' => "El ticket #{$ticketId} ha sido actualizado.",
                'link' => "/tickets/detail/{$ticketId}",
                'icon' => session()->get('imagen'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        if ($shouldRedirectToList) {
            return redirect()->to('/tickets')->with('mensaje', 'Ticket actualizado correctamente.');
        }

        return redirect()->to('/tickets/detail/' . $ticketId)->with('mensaje', 'Comentario añadido correctamente.');
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
