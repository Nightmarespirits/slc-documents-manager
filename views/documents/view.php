<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Auth.php';
require_once '../../classes/Document.php';

$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    header('Location: ../../index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$doc = new Document();
if (!$id || !$doc->getById($id)) {
    $_SESSION['error'] = 'Documento no encontrado';
    header('Location: list.php');
    exit;
}

$pageTitle = 'Ver Documento';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="h2 mt-3">Detalle del Documento</h1>
            <table class="table">
                <tr><th>ID</th><td><?php echo $doc->getId(); ?></td></tr>
                <tr><th>Nombre</th><td><?php echo htmlspecialchars($doc->getNombreOriginal()); ?></td></tr>
                <tr><th>Tipo Documento</th><td><?php echo $doc->getTipoDocumentoId(); ?></td></tr>
                <tr><th>Área</th><td><?php echo $doc->getAreaId(); ?></td></tr>
                <tr><th>Usuario</th><td><?php echo $doc->getUsuarioId(); ?></td></tr>
            </table>
            <a href="list.php" class="btn btn-secondary">Volver</a>
            <a href="../../<?php echo $doc->getRuta(); ?>" class="btn btn-primary" download>Descargar</a>
        </main>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
