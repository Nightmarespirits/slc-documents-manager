<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Auth.php';
require_once '../../classes/User.php';
require_once '../../classes/Document.php';

$auth = Auth::getInstance();
if(!$auth->isLoggedIn()){
    header('Location: ../../index.php');
    exit;
}

$db = Database::getInstance();

$pageTitle = 'Todos los Documentos';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>

<link href="../../assets/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../../assets/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Todos los Documentos</h1>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                <table id="tablaRegistros" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre Original</th>
                            <th>Area</th>
                            <th>Tipo Documento</th>
                            <th>Fecha Insercion</th>
                            <th>Usuario</th>
                            <th>Extension</th>
                            <th>Ultima Edicion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011-04-25</td>
                            <td>$320,800</td>
                            <td>$320,800</td>

                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nombre Original</th>
                            <th>Area</th>
                            <th>Tipo Documento</th>
                            <th>Fecha Insercion</th>
                            <th>Usuario</th>
                            <th>Extension</th>
                            <th>Ultima Edicion</th>

                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="../../assets/js/jquery-3.7.1.js"></script>
<script src="../../assets/js/popper.min.js"></script>
<script src="../../assets/js/bootstrap.min.js"></script>
<script src="../../assets/js/dataTables.js"></script>
<script src="../../assets/js/dataTables.bootstrap4.js"></script>

<script>
    new DataTable('#tablaRegistros');
</script>

<?php include '../../includes/footer.php'; ?>