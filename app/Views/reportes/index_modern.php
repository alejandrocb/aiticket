<!-- reportes/index_modern.php -->
<?php
/**
 * Informe resumen del dispositivo.
 *
 * La misma pantalla se consulta durante el dispositivo y se imprime al
 * terminar: el bloque @media print del final oculta los controles y la deja
 * en blanco y negro legible sobre papel.
 *
 * Las tablas van antes que las gráficas a propósito. Con unas decenas de
 * incidencias repartidas entre once grupos, la tabla dice exactamente qué ha
 * pasado y la gráfica solo lo insinúa.
 */
$minutos = $general['minutos_medios'];
$tiempoMedio = $minutos === null
    ? '—'
    : ($minutos >= 60 ? intdiv($minutos, 60) . ' h ' . ($minutos % 60) . ' min' : $minutos . ' min');
?>

<div class="flex flex-col gap-5 pb-10">

    <!-- Cabecera: solo se ve en papel -->
    <div class="hidden print:flex items-center gap-4 border-b border-black pb-3 mb-2">
        <?php if (etiqueta('logo')): ?>
            <img src="<?= base_url(etiqueta('logo')) ?>" alt="" class="h-14">
        <?php endif; ?>
        <div>
            <h1 class="text-xl font-bold"><?= esc(etiqueta('app')) ?></h1>
            <p class="text-sm">Informe resumen de incidencias</p>
        </div>
        <p class="ml-auto text-xs text-right">
            <?php if ($desde || $hasta): ?>
                Periodo: <?= esc($desde ?: 'inicio') ?> — <?= esc($hasta ?: 'hoy') ?><br>
            <?php endif; ?>
            Generado el <?= date('d/m/Y H:i') ?>
        </p>
    </div>

    <!-- Filtro de fechas -->
    <form method="get" action="<?= base_url('reportes') ?>" class="print:hidden flex flex-wrap items-end gap-3 bg-surface-light dark:bg-surface-dark rounded-xl border border-[#e5e7eb] dark:border-transparent p-4">
        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-text-secondary">Desde</label>
            <input type="date" name="desde" value="<?= esc($desde ?? '') ?>" class="form-input h-10 rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] px-3 text-sm">
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-text-secondary">Hasta</label>
            <input type="date" name="hasta" value="<?= esc($hasta ?? '') ?>" class="form-input h-10 rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] px-3 text-sm">
        </div>
        <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
            Aplicar
        </button>
        <a href="<?= base_url('reportes') ?>" class="h-10 px-4 rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] text-sm font-medium flex items-center hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors">
            Todo el histórico
        </a>
        <button type="button" onclick="window.print()" class="h-10 px-4 ml-auto rounded-lg border border-[#e5e7eb] dark:border-[#2c3b4a] text-sm font-medium flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors">
            <span class="material-symbols-outlined text-[18px]">print</span>
            Imprimir
        </button>
    </form>

    <!-- Cifras de cabecera -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <?php
        $tarjetas = [
            ['Incidencias', $general['total'], 'summarize'],
            ['Abiertas', $general['abiertas'], 'pending'],
            ['Cerradas', $general['cerradas'], 'task_alt'],
            ['Tiempo medio de cierre', $tiempoMedio, 'schedule'],
        ];
        foreach ($tarjetas as [$rotulo, $valor, $icono]): ?>
            <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark p-4 print:border-black">
                <div class="flex items-center gap-2 text-text-secondary mb-1">
                    <span class="material-symbols-outlined text-[18px] print:hidden"><?= $icono ?></span>
                    <span class="text-xs font-medium uppercase tracking-wide"><?= $rotulo ?></span>
                </div>
                <p class="text-2xl font-bold text-[#111418] dark:text-white print:text-black"><?= esc((string) $valor) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($general['total'] === 0): ?>
        <div class="rounded-xl border border-dashed border-[#e5e7eb] dark:border-[#2c3b4a] p-10 text-center">
            <p class="text-text-secondary">No hay incidencias en el periodo seleccionado.</p>
        </div>
    <?php else: ?>

    <!-- Por grupo de acción -->
    <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark overflow-hidden print:border-black">
        <h3 class="px-4 py-3 text-sm font-bold text-[#111418] dark:text-white border-b border-[#e5e7eb] dark:border-[#2c3b4a] print:text-black">
            Incidencias por <?= esc(strtolower(etiqueta('cliente'))) ?>
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-text-secondary border-b border-[#e5e7eb] dark:border-[#2c3b4a]">
                        <th class="px-4 py-2 font-medium"><?= esc(etiqueta('cliente')) ?></th>
                        <th class="px-4 py-2 font-medium text-right">Total</th>
                        <th class="px-4 py-2 font-medium text-right">Abiertas</th>
                        <th class="px-4 py-2 font-medium text-right">Cerradas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porGrupo as $fila): ?>
                        <tr class="border-b border-[#e5e7eb] dark:border-[#2c3b4a] last:border-0">
                            <td class="px-4 py-2 font-medium text-[#111418] dark:text-white print:text-black"><?= esc($fila['grupo']) ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= (int) $fila['total'] ?></td>
                            <td class="px-4 py-2 text-right"><?= (int) $fila['abiertas'] ?></td>
                            <td class="px-4 py-2 text-right"><?= (int) $fila['cerradas'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Desglose por tipología -->
    <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark overflow-hidden print:border-black print:break-inside-auto">
        <h3 class="px-4 py-3 text-sm font-bold text-[#111418] dark:text-white border-b border-[#e5e7eb] dark:border-[#2c3b4a] print:text-black">
            Detalle por tipología
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <tbody>
                    <?php foreach ($porTipologia as $grupo => $tipologias): ?>
                        <tr class="bg-gray-50 dark:bg-[#2c3b4a]/40 print:bg-transparent">
                            <td colspan="4" class="px-4 py-2 font-bold text-[#111418] dark:text-white print:text-black border-y border-[#e5e7eb] dark:border-[#2c3b4a]">
                                <?= esc($grupo) ?>
                            </td>
                        </tr>
                        <?php foreach ($tipologias as $fila): ?>
                            <tr class="border-b border-[#e5e7eb] dark:border-[#2c3b4a]">
                                <td class="px-4 py-2 pl-8"><?= esc($fila['tipologia'] ?? 'Sin tipología') ?></td>
                                <td class="px-4 py-2 text-right font-semibold w-20"><?= (int) $fila['total'] ?></td>
                                <td class="px-4 py-2 text-right w-24 text-text-secondary"><?= (int) $fila['abiertas'] ?> ab.</td>
                                <td class="px-4 py-2 text-right w-24 text-text-secondary"><?= (int) $fila['cerradas'] ?> cerr.</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 print:grid-cols-2">
        <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark p-4 print:border-black">
            <h3 class="text-sm font-bold text-[#111418] dark:text-white mb-3 print:text-black">Reparto por <?= esc(strtolower(etiqueta('cliente'))) ?></h3>
            <canvas id="grafica-grupos" height="220"></canvas>
        </div>
        <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark p-4 print:border-black">
            <h3 class="text-sm font-bold text-[#111418] dark:text-white mb-3 print:text-black">
                Evolución por <?= $evolucion['agrupacion'] === 'hora' ? 'hora' : 'día' ?>
            </h3>
            <canvas id="grafica-evolucion" height="220"></canvas>
        </div>
    </div>

    <?php endif; ?>
</div>

<style>
@media print {
    /* Fuera todo lo que no es el informe: menú lateral, cabecera, barra
       inferior y los propios controles del filtro. */
    body > div > div:first-child,
    .sticky,
    .fixed,
    #aviso-novedades { display: none !important; }

    body, .dark body { background: #fff !important; color: #000 !important; }

    /* El contenido ocupa el ancho completo al desaparecer el lateral. */
    body > div > div:last-child { margin-left: 0 !important; }

    table { page-break-inside: auto; }
    tr { page-break-inside: avoid; }
    thead { display: table-header-group; }

    @page { margin: 14mm; }
}
</style>

<?php if ($general['total'] > 0): ?>
<script>
(function () {
    'use strict';

    var grupos = <?= json_encode(array_map(static fn($f) => $f['grupo'], $porGrupo)) ?>;
    var totales = <?= json_encode(array_map(static fn($f) => (int) $f['total'], $porGrupo)) ?>;
    var momentos = <?= json_encode(array_map(static fn($f) => $f['momento'], $evolucion['filas'])) ?>;
    var conteos = <?= json_encode(array_map(static fn($f) => (int) $f['total'], $evolucion['filas'])) ?>;

    // Un solo color: las barras distinguen grupos por su etiqueta, no por
    // color, y en blanco y negro sobre papel una paleta no aporta nada.
    var AZUL = '#137fec';

    new Chart(document.getElementById('grafica-grupos'), {
        type: 'bar',
        data: { labels: grupos, datasets: [{ data: totales, backgroundColor: AZUL, borderRadius: 4 }] },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
            maintainAspectRatio: false,
            animation: false
        }
    });

    new Chart(document.getElementById('grafica-evolucion'), {
        type: 'line',
        data: {
            labels: momentos,
            datasets: [{ data: conteos, borderColor: AZUL, backgroundColor: 'rgba(19,127,236,.15)', fill: true, tension: .3 }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            maintainAspectRatio: false,
            animation: false
        }
    });
})();
</script>
<?php endif; ?>
