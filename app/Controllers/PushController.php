<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushController extends Controller
{
    public function subscribe()
    {
        $json = $this->request->getJSON();
        
        if (!$json || !isset($json->endpoint)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $userId = session()->get('id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autenticado']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('push_subscriptions');

        // Verificar si ya existe este endpoint para evitar duplicados
        $exists = $builder->where('endpoint', $json->endpoint)->countAllResults() > 0;

        if (!$exists) {
            $builder->insert([
                'user_id' => $userId,
                'endpoint' => $json->endpoint,
                'public_key' => $json->keys->p256dh ?? null,
                'auth_token' => $json->keys->auth ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function test()
    {
         // Solo para admins
         if (session()->get('rol_id') != 1) return redirect()->to('/');

         $auth = [
            'VAPID' => [
                'subject' => 'mailto:admin@acatife.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        $db = \Config\Database::connect();
        $subs = $db->table('push_subscriptions')->where('user_id', session()->get('id'))->get()->getResultArray();

        foreach ($subs as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub['endpoint'],
                'keys' => [
                    'p256dh' => $sub['public_key'],
                    'auth' => $sub['auth_token']
                ]
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode(['title' => 'Prueba Push', 'body' => 'Si ves esto, funciona!', 'url' => '/'])
            );
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            if ($report->isSuccess()) {
                echo "Mensaje enviado a {$endpoint}<br>";
            } else {
                echo "Fallo al enviar a {$endpoint}: {$report->getReason()}<br>";
                // Si falla por 410 Gone, borrar de DB
                if ($report->isSubscriptionExpired()) {
                     $db->table('push_subscriptions')->where('endpoint', $endpoint)->delete();
                     echo "Suscripción expirada eliminada.<br>";
                }
            }
        }
    }
}
