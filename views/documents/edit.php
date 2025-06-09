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

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'tipo_documento_id' => (int)$_POST['tipo_documento_id'],
        'area_id' => (int)$_POST['area_id']
    ];
    if ($doc->update($id, $data)) {
        $_SESSION['success'] = 'Documento actualizado';
        header('Location: view.php?id=' . $id);
        exit;
    }
    $_SESSION['error'] = 'Error al actualizar';
}

$tipos = $db->query("SELECT * FROM TIPOS_DOCUMENTO ORDER BY DESCRIPCION");
$areas = $db->query("SELECT * FROM AREAS ORDER BY NOMBRE");

$pageTitle = 'Editar Documento';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="h2 mt-3">Editar Documento</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tipo de Documento</label>
                    <select name="tipo_documento_id" class="form-select">
                        <?php while ($t = $tipos->fetch_assoc()): ?>
                            <option value="<?php echo $t['ID']; ?>" <?php echo $doc->getTipoDocumentoId() == $t['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($t['DESCRIPCION']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Área</label>
                    <select name="area_id" class="form-select">
                        <?php while ($a = $areas->fetch_assoc()): ?>
                            <option value="<?php echo $a['ID']; ?>" <?php echo $doc->getAreaId() == $a['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($a['NOMBRE']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="view.php?id=<?php echo $id; ?>" class="btn btn-secondary">Cancelar</a>
            </form>
        </main>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>
