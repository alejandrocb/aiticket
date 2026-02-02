

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url('js/scripts.js'); ?>"></script>
<script src="<?php echo base_url('js/datatables-simple-demo.js'); ?>"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('content').classList.toggle('shifted');
    });

        document.addEventListener('DOMContentLoaded', event => {
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                const body = document.body;
                if (body) {
                    body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', body.classList.contains('sb-sidenav-toggled'));
                }
            });
        }

        const ticketsByClientChart = document.getElementById('ticketsByClientChart');
        if (ticketsByClientChart) {
            const ctx1 = ticketsByClientChart.getContext('2d');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'pie',
                    data: {
                        labels: ['Cliente 1', 'Cliente 2', 'Cliente 3'],
                        datasets: [{
                            data: [10, 20, 30],
                            backgroundColor: ['#007bff', '#dc3545', '#ffc107']
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        }

        const oldestTicketsChart = document.getElementById('oldestTicketsChart');
        if (oldestTicketsChart) {
            const ctx2 = oldestTicketsChart.getContext('2d');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['Ticket 1', 'Ticket 2', 'Ticket 3'],
                        datasets: [{
                            label: 'Días abiertos',
                            data: [30, 25, 40],
                            backgroundColor: '#28a745'
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        }

    // Inicialización de DataTable
    document.addEventListener('DOMContentLoaded', event => {
        const datatablesSimple = document.getElementById('datatablesSimple');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    });
</script>
</body>
</html>
