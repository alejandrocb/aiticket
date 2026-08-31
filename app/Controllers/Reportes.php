<?php

namespace App\Controllers;

use App\Models\TicketModel;
use CodeIgniter\Controller;

/**
 * Informe resumen del dispositivo.
 *
 * Una sola pantalla que sirve para las dos cosas: se consulta durante el
 * dispositivo y, al imprimirla, sale el documento para entregar. Por eso no
 * hay generación de PDF en servidor: con la hoja de estilos de impresión, el
 * navegador produce el mismo resultado sin añadir dependencias.
 */
class Reportes extends Controller
{
    public function index()
    {
        $ticketModel = new TicketModel();

        $desde = $this->request->getGet('desde') ?: null;
        $hasta = $this->request->getGet('hasta') ?: null;

        // Se validan como fecha para no dejar pasar cualquier cosa a la
        // consulta, aunque el constructor ya parametriza los valores.
        $desde = $this->fechaValida($desde);
        $hasta = $this->fechaValida($hasta);

        $evolucion = $ticketModel->evolucion($desde, $hasta);

        $data = [
            'title'       => 'Informe resumen',
            'desde'       => $desde,
            'hasta'       => $hasta,
            'general'     => $ticketModel->resumenGeneral($desde, $hasta),
            'porGrupo'    => $ticketModel->resumenPorGrupo($desde, $hasta),
            'porTipologia' => $this->agruparTipologias($ticketModel->resumenPorTipologia($desde, $hasta)),
            'evolucion'   => $evolucion,
            'content'     => 'reportes/index_modern',
        ];

        return view('templates/layout_modern', $data);
    }

    /** Acepta solo AAAA-MM-DD; cualquier otra cosa se descarta. */
    private function fechaValida(?string $valor): ?string
    {
        if (! $valor || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
            return null;
        }

        return $valor;
    }

    /**
     * Convierte la lista plana de tipologías en un mapa por grupo, para que la
     * vista pueda pintarlas anidadas sin volver a consultar.
     */
    private function agruparTipologias(array $filas): array
    {
        $porGrupo = [];

        foreach ($filas as $fila) {
            $porGrupo[$fila['grupo']][] = $fila;
        }

        return $porGrupo;
    }
}
