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
$documento = new Document();
if (!$id || !$documento->getById($id)) {
    $_SESSION['error'] = 'Documento no encontrado';
    header('Location: list.php');
    exit;
}

$db = Database::getInstance();
$tipos_documento = $db->query("SELECT * FROM TIPOS_DOCUMENTO ORDER BY DESCRIPCION");
$areas = $db->query("SELECT * FROM AREAS ORDER BY NOMBRE");
$tipos_archivo = $db->query("SELECT * FROM TIPOS_ARCHIVO ORDER BY DESCRIPCION");

$pageTitle = 'Editar Documento';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Editar Documento</h1>
            </div>
            <form action="../../actions/update-document.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $documento->getId(); ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select class="form-select" name="tipo_documento_id" required>
                            <?php while ($tipo = $tipos_documento->fetch_assoc()): ?>
                                <option value="<?php echo $tipo['ID']; ?>" <?php echo $documento->getTipoDocumentoId() == $tipo['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($tipo['DESCRIPCION']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Área</label>
                        <select class="form-select" name="area_id" required>
                            <?php while ($area = $areas->fetch_assoc()): ?>
                                <option value="<?php echo $area['ID']; ?>" <?php echo $documento->getAreaId() == $area['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($area['NOMBRE']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Archivo</label>
                        <select class="form-select" name="tipo_archivo_id" required>
                            <?php while ($tipo = $tipos_archivo->fetch_assoc()): ?>
                                <option value="<?php echo $tipo['ID']; ?>" <?php echo $documento->getTipoArchivoId() == $tipo['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($tipo['DESCRIPCION']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reemplazar Archivo</label>
                        <input type="file" class="form-control" name="documento" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </main>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
