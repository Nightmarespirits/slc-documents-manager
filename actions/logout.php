<?php
// actions/logout.php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$auth = Auth::getInstance();
$auth->logout();

header('Location: ../index.php');
exit;
