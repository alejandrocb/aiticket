<!-- tickets/informe.php -->
<?php
/**
 * Informe imprimible de una incidencia.
 *
 * Mismo criterio que el informe resumen: una sola pantalla que se consulta y
 * se imprime, con el bloque @media print del final quitando de en medio el
 * menú, la cabecera y los controles.
 *
 * Los adjuntos se listan siempre por nombre y fecha; las imágenes se muestran
 * además en pequeño. En un documento que puede acabar en un expediente, saber
 * qué se adjuntó importa más que verlo a tamaño completo.
 */
$fmt = static fn(?string $f) => $f ? date('d/m/Y H:i', strtotime($f)) : '—';

/** Los adjuntos se guardan como JSON en la columna `media`. */
$adjuntos = static function (?string $json): array {
    if (empty($json)) {
        return [];
    }
    $items = json_decode($json, true);

    return is_array($items) ? $items : [];
};

$esImagen = static fn(array $item) => ($item['type'] ?? '') === 'image';
?>

<div class="flex flex-col gap-5 pb-10 max-w-4xl">

    <!-- Barra de acciones: no sale en papel -->
    <div class="print:hidden flex flex-wrap items-center gap-3">
        <a href="<?= base_url('tickets/detail/' . $ticket['id']) ?>" class="h-10 px-4 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] text-sm font-medium flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Volver al detalle
        </a>
        <button type="button" onclick="window.print()" class="h-10 px-4 ml-auto rounded-lg bg-primary text-white text-sm font-semibold flex items-center gap-2 hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-[18px]">print</span>
            Imprimir
        </button>
    </div>

    <!-- Cabecera del documento: solo en papel -->
    <div class="hidden print:flex items-center gap-4 border-b border-black pb-3">
        <?php if (etiqueta('logo')): ?>
            <?= marca('h-14 w-auto', '', true) ?>
        <?php endif; ?>
        <div>
            <h1 class="text-lg font-bold"><?= esc(etiqueta('app')) ?></h1>
            <p class="text-sm">Informe de incidencia n.º <?= (int) $ticket['id'] ?></p>
        </div>
        <p class="ml-auto text-xs text-right">
            <?= esc($ticket['escenario_nombre'] ?? '') ?><br>
            Generado el <?= date('d/m/Y H:i') ?>
        </p>
    </div>

    <!-- Ficha de la incidencia -->
    <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark p-5 print:border-black print:break-inside-avoid">
        <h2 class="text-lg font-bold text-[#111418] dark:text-white mb-4 print:text-black">
            Incidencia n.º <?= (int) $ticket['id'] ?>
        </h2>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
            <?php
            $campos = [
                etiqueta('cliente')     => $ticket['cliente_nombre'],
                'Tipología'             => $ticket['tipo_ticket_nombre'],
                'Estado'                => $ticket['estado_nombre'],
                'Prioridad'             => $ticket['prioridad_nombre'],
                'Responsable actual'    => $ticket['responsable_nombre'] ?: 'Sin asignar',
                'Registrada por'        => $ticket['creador_nombre'],
                'Fecha de registro'     => $fmt($ticket['fecha_creacion']),
                'Movimientos'           => count($movimientos),
            ];
            foreach ($campos as $rotulo => $valor): ?>
                <div class="flex justify-between gap-4 border-b border-dashed border-[#e5e7eb] dark:border-[#2c3b4a] pb-2 print:border-gray-400">
                    <dt class="text-text-secondary shrink-0"><?= esc($rotulo) ?></dt>
                    <dd class="font-medium text-[#111418] dark:text-white text-right print:text-black"><?= esc((string) ($valor ?? '—')) ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>

        <div class="mt-5">
            <p class="text-text-secondary text-sm mb-1">Descripción inicial</p>
            <p class="text-[#111418] dark:text-white whitespace-pre-line print:text-black"><?= esc($ticket['descripcion'] ?? '') ?></p>
        </div>

        <?php $adjuntosTicket = $adjuntos($ticket['media'] ?? null); ?>
        <?php if ($adjuntosTicket): ?>
            <div class="mt-4">
                <p class="text-text-secondary text-sm mb-2">Adjuntos del registro inicial</p>
                <ul class="text-sm space-y-1">
                    <?php foreach ($adjuntosTicket as $item): ?>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-text-secondary print:hidden"><?= $esImagen($item) ? 'image' : 'description' ?></span>
                            <a href="<?= base_url('upload/tickets/' . $item['filename']) ?>" target="_blank" class="text-primary hover:underline print:text-black"><?= esc(basename($item['filename'])) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <!-- Historial completo -->
    <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark overflow-hidden print:border-black">
        <h3 class="px-5 py-3 text-sm font-bold text-[#111418] dark:text-white border-b border-[#e5e7eb] dark:border-[#2c3b4a] print:text-black">
            Historial completo — <?= count($movimientos) ?> <?= count($movimientos) === 1 ? 'movimiento' : 'movimientos' ?>
        </h3>

        <?php if (empty($movimientos)): ?>
            <p class="p-5 text-sm text-text-secondary">Esta incidencia no tiene movimientos registrados.</p>
        <?php else: ?>
            <ol class="divide-y divide-[#e5e7eb] dark:divide-[#2c3b4a]">
                <?php foreach ($movimientos as $i => $mov): ?>
                    <li class="p-5 print:break-inside-avoid">
                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-2">
                            <span class="text-xs font-bold text-text-secondary print:text-black"><?= $i + 1 ?>.</span>
                            <span class="text-sm font-semibold text-[#111418] dark:text-white print:text-black">
                                <?= esc($mov['usuario_nombre'] ?? 'Sistema') ?>
                            </span>
                            <span class="text-xs text-text-secondary"><?= $fmt($mov['fecha_movimiento']) ?></span>
                            <?php if (! empty($mov['tipo_movimiento'])): ?>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-[#2c3b4a] text-text-secondary print:border print:border-gray-400 print:bg-transparent">
                                    <?= esc($mov['tipo_movimiento']) ?>
                                </span>
                            <?php endif; ?>
                            <?php if (! empty($mov['auto'])): ?>
                                <span class="text-xs text-text-secondary italic">automático</span>
                            <?php endif; ?>
                        </div>

                        <p class="text-sm text-[#111418] dark:text-white whitespace-pre-line print:text-black"><?= esc($mov['descripcion'] ?? '') ?></p>

                        <?php $adjuntosMov = $adjuntos($mov['media'] ?? null); ?>
                        <?php if ($adjuntosMov): ?>
                            <ul class="mt-2 text-xs space-y-1">
                                <?php foreach ($adjuntosMov as $item): ?>
                                    <li class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[14px] text-text-secondary print:hidden"><?= $esImagen($item) ? 'image' : 'description' ?></span>
                                        <a href="<?= base_url('upload/tickets/' . $item['filename']) ?>" target="_blank" class="text-primary hover:underline print:text-black"><?= esc(basename($item['filename'])) ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>

    <p class="hidden print:block text-xs border-t border-black pt-2">
        <?= esc(etiqueta('app')) ?> — Informe de la incidencia n.º <?= (int) $ticket['id'] ?>, generado el <?= date('d/m/Y \a \l\a\s H:i') ?>.
    </p>
</div>

<style>
@media print {
    /* Menú lateral, cabecera pegajosa, barra inferior y controles fuera. */
    body > div > div:first-child,
    .sticky,
    .fixed,
    #aviso-novedades { display: none !important; }

    body, .dark body { background: #fff !important; color: #000 !important; }
    body > div > div:last-child { margin-left: 0 !important; }

    @page { margin: 14mm; }
}
</style>
