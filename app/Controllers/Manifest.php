<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Manifiesto de la PWA, generado en vez de servido como fichero estático.
 *
 * Las dos instalaciones comparten código pero no marca: con un manifest.json
 * fijo las dos se instalarían en el móvil llamándose igual y con el mismo
 * icono. Aquí toma el nombre y el color de Config\Etiquetas, así que cada una
 * se instala con lo suyo.
 *
 * Para que Chrome ofrezca instalar hacen falta tres cosas: servirse por HTTPS,
 * un service worker registrado y este manifiesto con iconos de 192 y 512
 * píxeles que existan de verdad. Si los ficheros no están, el navegador se
 * limita a ofrecer un acceso directo.
 */
class Manifest extends Controller
{
    public function index()
    {
        $nombre = etiqueta('app');

        $manifiesto = [
            // Identidad estable de la aplicación instalada. Si cambia, el
            // navegador la considera otra aplicación distinta.
            'id'               => base_url('/'),
            'name'             => $nombre,
            'short_name'       => mb_substr($nombre, 0, 12),
            'description'      => 'Gestión de incidencias y avisos en tiempo real.',
            'start_url'        => base_url('dashboard'),
            'scope'            => base_url('/'),
            'display'          => 'standalone',
            'background_color' => '#ffffff',
            'theme_color'      => etiqueta('colorTema'),
            'icons'            => $this->iconos(),
        ];

        return $this->response
                    ->setContentType('application/manifest+json')
                    ->setBody(json_encode($manifiesto, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Un único fichero de 512 declarado dos veces, para 'any' y para
     * 'maskable', en lugar de uno por tamaño.
     *
     * Chrome necesita al menos un icono de 192 o más y recomienda 512 para la
     * pantalla de arranque; con uno de 512 se cumplen las dos cosas y el
     * navegador lo reescala solo. Se declaran por separado y no como
     * "any maskable" porque Android recorta el maskable a un círculo, y un
     * icono declarado para ambos usos pierde los bordes en el recorte.
     *
     * Sin icono configurado se devuelve una lista vacía: el manifiesto sigue
     * siendo válido, pero el navegador no ofrecerá instalar la aplicación.
     */
    private function iconos(): array
    {
        $icono = etiqueta('icono');

        if ($icono === '') {
            return [];
        }

        $url = base_url($icono);

        return [
            ['src' => $url, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => $url, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
        ];
    }
}
