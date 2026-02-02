<!-- dashboard_modern.php -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Top 5 Clientes -->
    <div class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent">
        <h6 class="text-primary font-bold mb-4">Top 5 Clientes (Último Mes)</h6>
        <div class="relative h-64">
            <canvas id="ticketsLastMonthTop5Chart"></canvas>
        </div>
    </div>

    <!-- Tickets Last 10 Days -->
    <div class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent">
        <h6 class="text-primary font-bold mb-4">Tickets por Día (Últimos 10 Días)</h6>
        <div class="relative h-64">
             <canvas id="ticketsLast10DaysChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ticketsLastMonthTop5 = <?= json_encode($ticketsLastMonthTop5); ?>;
        var ticketsLast10Days = <?= json_encode($ticketsLast10Days); ?>;
        var movimientosLast10Days = <?= json_encode($movimientosLast10Days); ?>;

        var ctxLastMonthTop5 = document.getElementById('ticketsLastMonthTop5Chart').getContext('2d');
        var ctxLast10Days = document.getElementById('ticketsLast10DaysChart').getContext('2d');

        var labelsLastMonthTop5 = ticketsLastMonthTop5.map(function (ticket) {
            return ticket.cliente_nombre;
        });
        var dataLastMonthTop5 = ticketsLastMonthTop5.map(function (ticket) {
            return ticket.total_tickets;
        });

        var labelsLast10Days = ticketsLast10Days.map(function (ticket) {
            return ticket.fecha;
        });
        var dataTicketsLast10Days = ticketsLast10Days.map(function (ticket) {
            return ticket.total_tickets;
        });

        var dataMovimientosLast10Days = movimientosLast10Days.map(function (movimiento) {
            return movimiento.total_movimientos;
        });

        // Set generic dark mode Chart defaults if needed, though simpler to keep default for now
        Chart.defaults.color = '#9ca3af'; 
        Chart.defaults.borderColor = '#374151';

        new Chart(ctxLastMonthTop5, {
            type: 'pie',
            data: {
                labels: labelsLastMonthTop5,
                datasets: [{
                    data: dataLastMonthTop5,
                    backgroundColor: ['#137fec', '#22c55e', '#06b6d4', '#eab308', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        new Chart(ctxLast10Days, {
            type: 'bar',
            data: {
                labels: labelsLast10Days,
                datasets: [
                    {
                        label: 'Tickets',
                        data: dataTicketsLast10Days,
                        backgroundColor: '#137fec',
                    },
                    {
                        label: 'Movimientos',
                        data: dataMovimientosLast10Days,
                        backgroundColor: '#22c55e',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    }
                }
            }
        });
    });
</script>
