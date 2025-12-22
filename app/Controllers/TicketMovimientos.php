<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\TicketModel;
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
        $data = [
            'ticket_id' => $ticketId,
            'tipo_movimiento' => $this->request->getPost('tipo_movimiento'),
            'descripcion' => $this->request->getPost('descripcion'),
            'usuario_id' => session()->get('id'),
            'media' => json_encode($mediaFiles)
        ];

        if ($this->model->insert($data)) {
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
                    $notificationModel->insert([
                        'user_id' => $recipientId,
                        'title' => 'Nuevo detalle en ticket',
                        'message' => "Se ha añadido un nuevo detalle al ticket #{$ticketId}.",
                        'link' => "/tickets/detail/{$ticketId}",
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
