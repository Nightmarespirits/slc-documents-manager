<?php
session_start();
require_once '../classes/Auth.php';

Auth::getInstance()->logout();
header('Location: ../index.php');
exit;
?>
