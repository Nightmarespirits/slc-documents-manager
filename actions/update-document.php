<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/Document.php';

$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $data = [
        'tipo_documento_id' => $_POST['tipo_documento_id'],
        'area_id' => $_POST['area_id'],
        'tipo_archivo_id' => $_POST['tipo_archivo_id']
    ];
    $file = isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK ? $_FILES['documento'] : null;

    $doc = new Document();
    if ($doc->update($id, $data, $file)) {
        $_SESSION['success'] = 'Documento actualizado correctamente';
    } else {
        $_SESSION['error'] = 'Error al actualizar el documento';
    }
}
header('Location: ../views/documents/list.php');
exit;
?>
