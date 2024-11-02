<?php
// actions/login.php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $auth = Auth::getInstance();
    
    if ($auth->login($email, $password)) {
        header('Location: ../views/dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Credenciales inválidas';
        header('Location: ../index.php');
        exit;
    }
}

header('Location: ../index.php');
exit;