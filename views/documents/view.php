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
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ver Documento</h1>
            </div>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($doc->getNombreOriginal()); ?></p>
            <a href="<?php echo $doc->getRuta(); ?>" class="btn btn-primary" download>Descargar</a>
            <div class="mt-4">
<?php
$ext = strtolower(pathinfo($doc->getRuta(), PATHINFO_EXTENSION));
if ($ext === 'pdf') {
    echo '<iframe src="'.$doc->getRuta().'" style="width:100%;height:600px;" frameborder="0"></iframe>';
} else {
    echo '<p>Previsualización no disponible.</p>';
}
?>
            </div>
        </main>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
