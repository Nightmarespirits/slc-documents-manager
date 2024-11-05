<?php
// actions/update-profile.php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/User.php';

$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

$user = new User();
$user->loadById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_info':
            // Validar campos
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $telefono = trim($_POST['telefono']);

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = 'El nombre y el email son obligatorios';
                header('Location: ../views/profile.php');
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'El email no es válido';
                header('Location: ../views/profile.php');
                exit;
            }

            // Verificar si el email ya está en uso por otro usuario
            if ($user->emailExists($email) && $email !== $user->getEmail()) {
                $_SESSION['error'] = 'El email ya está registrado por otro usuario';
                header('Location: ../views/profile.php');
                exit;
            }

            // Actualizar información
            if ($user->updateProfile($nombre, $email, $telefono)) {
                $_SESSION['success'] = 'Perfil actualizado correctamente';
                // Registrar actividad
                $user->logActivity('Actualización de perfil', 'Información personal actualizada');
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
            }
            break;

        case 'change_password':
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validar contraseña actual
            if (!$user->verifyPassword($currentPassword)) {
                $_SESSION['error'] = 'La contraseña actual es incorrecta';
                header('Location: ../views/profile.php');
                exit;
            }

            // Validar nueva contraseña
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Las contraseñas no coinciden';
                header('Location: ../views/profile.php');
                exit;
            }

            // Validar requisitos de contraseña
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $newPassword)) {
                $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números';
                header('Location: ../views/profile.php');
                exit;
            }

            // Actualizar contraseña
            if ($user->updatePassword($newPassword)) {
                $_SESSION['success'] = 'Contraseña actualizada correctamente';
                // Registrar actividad
                $user->logActivity('Cambio de contraseña', 'Contraseña actualizada');
            } else {
                $_SESSION['error'] = 'Error al actualizar la contraseña';
            }
            break;

        default:
            $_SESSION['error'] = 'Acción no válida';
            break;
    }
}

header('Location: ../views/profile.php');
exit;