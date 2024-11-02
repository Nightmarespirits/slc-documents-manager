<?php
// actions/upload-document.php (versión completa)
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
    try {
        // Validar archivo
        if (!isset($_FILES['documento']) || $_FILES['documento']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error al subir el archivo");
        }

        $file = $_FILES['documento'];
        
        // Validar tipo de archivo
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception("Tipo de archivo no permitido");
        }

        // Validar tamaño (20MB máximo)
        $max_size = 20 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            throw new Exception("El archivo es demasiado grande");
        }

        // Preparar datos
        $data = [
            'tipo_documento_id' => $_POST['tipo_documento_id'],
            'area_id' => $_POST['area_id'],
            'tipo_archivo_id' => $_POST['tipo_archivo_id'],
            'usuario_id' => $_SESSION['user_id']
        ];

        // Crear documento
        $documento = new Document();
        if ($documento->create($data, $file)) {
            $_SESSION['success'] = "Documento subido correctamente";
            header('Location: ../views/documents/list.php');
            exit;
        } else {
            throw new Exception("Error al guardar el documento");
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../views/documents/create.php');
        exit;
    }
}

$_SESSION['error'] = "Método no permitido";
header('Location: ../views/documents/create.php');
exit;