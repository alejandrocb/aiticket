<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'title', 'message', 'link', 'icon', 'is_read', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Callbacks
    protected $afterInsert = ['triggerPushAfterInsert'];

    protected function triggerPushAfterInsert(array $data)
    {
        if (isset($data['id'])) {
            $notification = $this->find($data['id']);
            if ($notification) {
                $this->sendPushNotification(
                    $notification['user_id'],
                    $notification['title'],
                    $notification['message'],
                    $notification['link'],
                    $notification['icon'] ?? null
                );
            }
        }
        return $data;
    }

    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function getNotifications($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Envía una notificación push a todos los dispositivos suscritos de un usuario
     */
    public function sendPushNotification($userId, $title, $message, $link = '/', $icon = null)
    {
        $db = \Config\Database::connect();
        $subs = $db->table('push_subscriptions')
                   ->where('user_id', $userId)
                   ->get()
                   ->getResultArray();

        if (empty($subs)) return;

        $auth = [
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT') ?: 'mailto:admin@acatife.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        try {
            $webPush = new \Minishlink\WebPush\WebPush($auth);
            
            // Convert icon to absolute URL if it exists
            $iconUrl = $icon ? base_url($icon) : base_url('assets/images/icon-192.png');

            $payload = json_encode([
                'title' => $title,
                'body'  => $message,
                'url'   => $link,
                'icon'  => $iconUrl
            ]);

            foreach ($subs as $sub) {
                $subscription = \Minishlink\WebPush\Subscription::create([
                    'endpoint' => $sub['endpoint'],
                    'keys' => [
                        'p256dh' => $sub['public_key'],
                        'auth' => $sub['auth_token']
                    ]
                ]);

                $webPush->queueNotification($subscription, $payload);
            }

            foreach ($webPush->flush() as $report) {
                if (!$report->isSuccess() && $report->isSubscriptionExpired()) {
                    // Limpiar suscripciones obsoletas
                    $db->table('push_subscriptions')
                       ->where('endpoint', $report->getRequest()->getUri()->__toString())
                       ->delete();
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error enviando Push: ' . $e->getMessage());
        }
    }
}
