/*!
* Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
*/

// Scripts

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Placeholder para los gráficos
    const ticketsByClientChartElem = document.getElementById('ticketsByClientChart');
    if (ticketsByClientChartElem) {
        const ctx1 = ticketsByClientChartElem.getContext('2d');
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

    const oldestTicketsChartElem = document.getElementById('oldestTicketsChart');
    if (oldestTicketsChartElem) {
        const ctx2 = oldestTicketsChartElem.getContext('2d');
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

    const toggles = document.querySelectorAll('.toggle-movement');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function(event) {
            event.preventDefault();
            const ticket = this.closest('.list-group-item');
            const shortMovement = ticket.querySelector('.ticket-short-movement');
            const fullMovement = ticket.querySelector('.ticket-full-movement');
            shortMovement.classList.toggle('d-none');
            fullMovement.classList.toggle('d-none');
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // Add event listener for image links to show the modal with the full image
    const imageLinks = document.querySelectorAll('.movement-image-link');
    imageLinks.forEach(link => {
        link.addEventListener('click', function() {
            const imgSrc = this.getAttribute('data-img-src');
            const modalImage = document.querySelector('#modalImage');
            modalImage.setAttribute('src', imgSrc);
        });
    });

    // Manejar el cambio de imagen en el select de responsables
    const select = document.getElementById('responsable_id');
    const responsableImg = document.getElementById('responsable_img');

    if (select && responsableImg) {
        // Actualiza la imagen cuando cambia el valor del select
        select.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const imgSrc = selectedOption.getAttribute('data-img-src');
            responsableImg.src = imgSrc;
        });

        // Configurar la imagen inicial
        const initialSelectedOption = select.options[select.selectedIndex];
        const initialImgSrc = initialSelectedOption.getAttribute('data-img-src');
        responsableImg.src = initialImgSrc;
    }
});
