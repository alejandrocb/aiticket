<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Notifications extends Controller
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $userId = session()->get('id');
        $notifications = $this->notificationModel
                                ->where('user_id', $userId)
                                ->orderBy('created_at', 'DESC')
                                ->findAll();
        
        // Mark all as read when viewing list? Or just show them.
        // Usually list shows read/unread. User can click to mark read or click "Mark all read".

        $data = [
            'title' => 'Mis Notificaciones',
            'notifications' => $notifications,
            'content' => 'notifications/index'
        ];

        echo view('templates/layout', $data);
    }

    public function list()
    {
         $userId = session()->get('id');
         
         // Standard Model usage
         $notifications = $this->notificationModel
                                ->where('user_id', $userId)
                                ->orderBy('created_at', 'DESC')
                                ->findAll(5);
                                
         $unreadCount = $this->notificationModel
                               ->where('user_id', $userId)
                               ->where('is_read', 0)
                               ->countAllResults();

         return $this->response->setJSON([
             'notifications' => $notifications,
             'unread_count' => $unreadCount
         ]);
    }

    public function markRead($id)
    {
        $this->notificationModel->update($id, ['is_read' => 1]);
        // If it has a link, redirect to it?
        // Or if called via AJAX, return success.
        
        $notification = $this->notificationModel->find($id);
        if ($notification && !empty($notification['link'])) {
            return redirect()->to($notification['link']);
        }
        
        return redirect()->back();
    }

    public function markAllRead()
    {
        $userId = session()->get('id');
        $this->notificationModel->where('user_id', $userId)->set(['is_read' => 1])->update();
        return redirect()->back();
    }
}
