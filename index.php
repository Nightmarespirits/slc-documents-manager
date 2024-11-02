<?php
// index.php
session_start();
require_once 'config/database.php';
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

$auth = Auth::getInstance();
if ($auth->isLoggedIn()) {
    header('Location: views/dashboard.php');
    exit;
}

// Redirigir al login
header('Location: views/login.php');
exit;