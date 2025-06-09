<?php
session_start();
$pageTitle = 'Página no encontrada';
include 'includes/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-4">404</h1>
    <p class="lead">La página que buscas no existe.</p>
    <a href="index.php" class="btn btn-primary">Ir al inicio</a>
</div>
<?php include 'includes/footer.php'; ?>
