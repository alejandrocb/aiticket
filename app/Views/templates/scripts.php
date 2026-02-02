<!-- Scripts -->
<script src="<?php echo base_url('js/jquery-3.5.1.min.js'); ?>"></script>
<script src="<?php echo base_url('js/popper.min.js'); ?>"></script>
<script src="<?php echo base_url('js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('js/chart.js'); ?>"></script>
<script src="<?php echo base_url('js/dropzone.min.js'); ?>"></script>
<script src="<?php echo base_url('js/lightbox.min.js'); ?>"></script>
<script src="<?php echo base_url('js/scripts.js'); ?>"></script>

<script>
    // Tu script aquí
    document.addEventListener('DOMContentLoaded', function() {
        const responsableSelect = document.getElementById('responsable_id');
        const responsableImg = document.getElementById('responsable_img');

        if (responsableSelect && responsableImg) {
            responsableSelect.addEventListener('change', function() {
                const selectedOption = responsableSelect.options[responsableSelect.selectedIndex];
                const imgSrc = selectedOption.getAttribute('data-img-src');
                responsableImg.setAttribute('src', imgSrc);
            });
        }
    });
</script>