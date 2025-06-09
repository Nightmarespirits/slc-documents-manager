<?php
session_start();
$pageTitle = 'Acceso denegado';
include 'includes/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-4">403</h1>
    <p class="lead">No tienes permiso para acceder a esta página.</p>
    <a href="index.php" class="btn btn-primary">Ir al inicio</a>
</div>
<?php include 'includes/footer.php'; ?>
