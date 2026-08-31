<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'title', 'message', 'link', 'icon', 'image', 'is_read', 'created_at'];
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
                    $notification['icon'] ?? null,
                    $notification['image'] ?? null
                );
            }
        }
        return $data;
    }

    /**
     * Crea una notificación por cada destinatario final.
     *
     * A los destinatarios que se le pasen añade siempre los usuarios marcados
     * con `recibe_todas_notificaciones` —Dirección en el Puesto de Mando—, que
     * deben enterarse de todo pase lo que pase.
     *
     * Descarta a `$autorId`: quien acaba de hacer el cambio no necesita que le
     * avisen de su propia acción. Si Dirección es quien lo hace, tampoco se
     * autonotifica.
     *
     * @param array $destinatarios ids de usuario; se admiten nulos y repetidos
     * @param array $datos         campos de la notificación, sin `user_id`
     * @param int|null $autorId    quien provoca el aviso, para excluirlo
     *
     * @return int número de notificaciones creadas
     */
    public function notificarA(array $destinatarios, array $datos, $autorId = null): int
    {
        $usuarioModel = new UsuarioModel();
        $direccion    = $usuarioModel->where('recibe_todas_notificaciones', 1)->findColumn('id') ?? [];

        $finales = array_unique(array_filter(
            array_merge($destinatarios, $direccion),
            static fn($id) => ! empty($id) && $id != $autorId
        ));

        foreach ($finales as $userId) {
            $this->insert(array_merge($datos, ['user_id' => $userId]));
        }

        return count($finales);
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
    public function sendPushNotification($userId, $title, $message, $link = '/', $icon = null, $image = null)
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
            $userPhotoUrl = $icon ? base_url($icon) : null;

            $payload = json_encode([
                'title' => $title,
                'body'  => $message,
                'url'   => $link,
                'userPhoto' => $userPhotoUrl,
                'image' => $image ? base_url('upload/tickets/' . $image) : null,
                // El pictograma de la barra de estado viaja en el aviso porque
                // el service worker es un fichero estático y no puede leer la
                // configuración de la instalación.
                'badge' => etiqueta('badge') ? base_url(etiqueta('badge')) : null,
                'icono' => etiqueta('icono') ? base_url(etiqueta('icono')) : null,
                'tag'   => 'ticket-' . uniqid()
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
