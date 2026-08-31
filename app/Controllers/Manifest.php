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
            'icons'            => [
                [
                    'src'     => base_url('images/icon-192.png'),
                    'sizes'   => '192x192',
                    'type'    => 'image/png',
                    'purpose' => 'any',
                ],
                [
                    'src'     => base_url('images/icon-512.png'),
                    'sizes'   => '512x512',
                    'type'    => 'image/png',
                    'purpose' => 'any',
                ],
                // Maskable aparte y no combinado con 'any': Android recorta el
                // maskable a un círculo, y un icono pensado para verse entero
                // pierde los bordes si se declara para las dos cosas.
                [
                    'src'     => base_url('images/icon-512.png'),
                    'sizes'   => '512x512',
                    'type'    => 'image/png',
                    'purpose' => 'maskable',
                ],
            ],
        ];

        return $this->response
                    ->setContentType('application/manifest+json')
                    ->setBody(json_encode($manifiesto, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
