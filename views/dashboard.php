<?php
// dashboard.php
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

$pageTitle = 'Dashboard - Sistema de Documentos';
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <div class="row">
                <!-- Tarjeta de Documentos Recientes -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-file-alt text-primary icon-lg"></i> 
                                Documentos Recientes
                            </h5>
                            <p class="card-text">
                                Últimos documentos subidos al sistema.
                            </p>
                            <a href="documents/list.php" class="btn btn-primary">
                                Ver Documentos
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Subir Nuevo -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-upload text-success icon-lg"></i>
                                Subir Nuevo Documento
                            </h5>
                            <p class="card-text">
                                Sube un nuevo documento al sistema.
                            </p>
                            <a href="documents/create.php" class="btn btn-success">
                                Subir Documento
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Perfil -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-user text-info icon-lg"></i>
                                Mi Perfil
                            </h5>
                            <p class="card-text">
                                Gestiona tu información personal.
                            </p>
                            <a href="profile.php" class="btn btn-info text-white">
                                Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    .icon-lg {
        font-size: 3rem; /* Aumenta el tamaño del ícono */
        display: block; /* Centra el ícono en la línea */
        margin: 0 auto; /* Asegura que el ícono esté centrado */
    }
</style>

<?php include '../includes/footer.php'; ?>
