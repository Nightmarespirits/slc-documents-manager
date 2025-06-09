<?php
// actions/delete-document.php
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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    $_SESSION['error'] = 'ID de documento no válido';
    header('Location: ../views/documents/list.php');
    exit;
}

$doc = new Document();
if ($doc->delete($id)) {
    $_SESSION['success'] = 'Documento eliminado correctamente';
} else {
    $_SESSION['error'] = 'No se pudo eliminar el documento';
}
header('Location: ../views/documents/list.php');
exit;
