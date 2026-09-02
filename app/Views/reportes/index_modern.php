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
            <?= marca('h-14 w-auto', '', true) ?>
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
            <h3 class="text-sm font-bold text-[#111418] dark:text-white mb-3 print:text-black">
                Comparativa por <?= esc(strtolower(etiqueta('cliente'))) ?>
            </h3>
            <!-- Altura fija y position:relative. Chart.js redimensiona el
                 lienzo al tamaño del contenedor; si este no tiene altura
                 propia, crecen el uno al otro indefinidamente. -->
            <div class="grafica-caja relative h-80">
                <canvas id="grafica-grupos"></canvas>
            </div>
        </div>
        <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark p-4 print:border-black">
            <h3 class="text-sm font-bold text-[#111418] dark:text-white mb-3 print:text-black">
                Reparto del total
            </h3>
            <div class="grafica-caja relative h-80">
                <canvas id="grafica-reparto"></canvas>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-[#e5e7eb] dark:border-transparent bg-surface-light dark:bg-surface-dark p-4 print:border-black">
        <h3 class="text-sm font-bold text-[#111418] dark:text-white mb-3 print:text-black">
            Evolución por <?= $evolucion['agrupacion'] === 'hora' ? 'hora' : 'día' ?>
        </h3>
        <div class="grafica-caja relative h-64">
            <canvas id="grafica-evolucion"></canvas>
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

    /* Las gráficas necesitan una altura concreta en papel: el lienzo se
       dimensiona contra su contenedor, y en milímetros no depende del ancho
       de la ventana desde la que se imprima. */
    .grafica-caja {
        height: 62mm !important;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    /* Sin esto el lienzo conserva el tamaño en píxeles con el que se dibujó
       en pantalla y se sale de la caja. */
    .grafica-caja canvas {
        max-width: 100% !important;
        max-height: 100% !important;
    }

    @page { margin: 14mm; }
}
</style>

<?php if ($general['total'] > 0): ?>
<script>
(function () {
    'use strict';

    var grupos = <?= json_encode(array_map(static fn($f) => $f['grupo'], $porGrupo)) ?>;
    var totales = <?= json_encode(array_map(static fn($f) => (int) $f['total'], $porGrupo)) ?>;
    var abiertas = <?= json_encode(array_map(static fn($f) => (int) $f['abiertas'], $porGrupo)) ?>;
    var cerradas = <?= json_encode(array_map(static fn($f) => (int) $f['cerradas'], $porGrupo)) ?>;
    var momentos = <?= json_encode(array_map(static fn($f) => $f['momento'], $evolucion['filas'])) ?>;
    var conteos = <?= json_encode(array_map(static fn($f) => (int) $f['total'], $evolucion['filas'])) ?>;

    var AZUL = '#137fec';
    var VERDE = '#16a34a';

    /**
     * Paleta del anillo: familias de color intercaladas y luminosidad
     * alternada entre oscuro y claro.
     *
     * Las dos cosas a la vez, y por motivos distintos. El tono cambia en cada
     * porción para que en pantalla se distingan aunque solo haya tres o cuatro
     * grupos con incidencias: agrupadas por familia, los primeros salían todos
     * azules. Y la luminosidad se alterna porque el informe se imprime, muchas
     * veces en blanco y negro, donde el tono desaparece y solo queda el brillo:
     * colores distintos pero de luminosidad parecida acaban siendo el mismo
     * gris.
     */
    var PALETA = [
        '#0b3d69', // azul oscuro
        '#f59e0b', // ámbar
        '#7f1d1d', // granate
        '#93c9fa', // azul claro
        '#0f766e', // verde azulado
        '#f7b8ad', // rosa claro
        '#6b21a8', // morado
        '#86efac', // verde claro
        '#713f12', // marrón
        '#a5b4fc', // lavanda
        '#137fec'  // azul medio
    ];

    var graficas = [];

    // Comparativa: abiertas y cerradas apiladas, para ver de un vistazo cuánto
    // registró cada grupo y cuánto queda por resolver. Los grupos sin
    // incidencias aparecen igualmente, con la barra a cero.
    graficas.push(new Chart(document.getElementById('grafica-grupos'), {
        type: 'bar',
        data: {
            labels: grupos,
            datasets: [
                { label: 'Abiertas', data: abiertas, backgroundColor: AZUL },
                { label: 'Cerradas', data: cerradas, backgroundColor: VERDE }
            ]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
            scales: {
                x: { stacked: true, beginAtZero: true, ticks: { precision: 0 } },
                y: { stacked: true, ticks: { font: { size: 10 } } }
            },
            responsive: true,
            maintainAspectRatio: false,
            animation: false
        }
    }));

    // Reparto del total. Se excluyen los grupos a cero: una porción vacía no
    // se ve y solo ensucia la leyenda.
    var repartoEtiquetas = [], repartoDatos = [], repartoColores = [];
    grupos.forEach(function (nombre, i) {
        if (totales[i] > 0) {
            repartoColores.push(PALETA[repartoEtiquetas.length % PALETA.length]);
            repartoEtiquetas.push(nombre);
            repartoDatos.push(totales[i]);
        }
    });

    graficas.push(new Chart(document.getElementById('grafica-reparto'), {
        type: 'doughnut',
        data: {
            labels: repartoEtiquetas,
            datasets: [{ data: repartoDatos, backgroundColor: repartoColores, borderColor: '#fff', borderWidth: 1 }]
        },
        options: {
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var suma = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                            var pct = suma ? Math.round(ctx.parsed / suma * 100) : 0;
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            animation: false
        }
    }));

    graficas.push(new Chart(document.getElementById('grafica-evolucion'), {
        type: 'line',
        data: {
            labels: momentos,
            datasets: [{ data: conteos, borderColor: AZUL, backgroundColor: 'rgba(19,127,236,.15)', fill: true, tension: .3 }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            responsive: true,
            maintainAspectRatio: false,
            animation: false
        }
    }));

    /**
     * Redibujado al entrar y salir de impresión.
     *
     * Al imprimir cambia el ancho útil de la página —desaparece el menú y se
     * anula el margen izquierdo— y el lienzo conserva el tamaño en píxeles con
     * el que se dibujó en pantalla, así que se desborda de su caja.
     *
     * Se usa matchMedia('print') y no solo el evento beforeprint porque este
     * último se dispara antes de que se apliquen los estilos de impresión: al
     * recalcular en ese momento, la gráfica se mide todavía contra el ancho de
     * pantalla. El cambio de media query sí ocurre con la maquetación de papel
     * ya aplicada.
     */
    function recalcular() {
        graficas.forEach(function (g) { g.resize(); });
    }

    if (window.matchMedia) {
        var medioImpresion = window.matchMedia('print');
        var alCambiar = function () { recalcular(); };

        if (medioImpresion.addEventListener) {
            medioImpresion.addEventListener('change', alCambiar);
        } else if (medioImpresion.addListener) {
            medioImpresion.addListener(alCambiar); // navegadores antiguos
        }
    }

    // Respaldo para navegadores que no notifican el cambio de media query.
    window.addEventListener('beforeprint', recalcular);
    window.addEventListener('afterprint', recalcular);
})();
</script>
<?php endif; ?>
