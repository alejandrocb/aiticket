<?php include(APPPATH . 'Views/templates/header.php'); ?>
<?php include(APPPATH . 'Views/templates/navbar.php'); ?>

<div id="layoutSidenav">
    <?php include(APPPATH . 'Views/templates/sidebar.php'); ?>
    <div id="layoutSidenav_content">
        <main class="flex-shrink-0 mb-5">
            <div class="container-fluid px-4">
                <?php include(APPPATH . "Views/{$content}.php"); ?>
                
                <!-- Modal -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Imagen del Movimiento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="" id="modalImage" class="img-fluid" alt="Imagen del movimiento">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </main>
        <footer class="footer mt-auto py-4 bg-light custom-footer">
            <div class="container">
                <?php include(APPPATH . 'Views/templates/footer.php'); ?>
            </div>
        </footer>
        <?php include(APPPATH . 'Views/templates/scripts.php'); ?>
    </div>
</div>

</body>
</html>