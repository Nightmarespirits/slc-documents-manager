<?php
// views/documents/create.php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Auth.php';
require_once '../../classes/User.php';
require_once '../../classes/Document.php';

$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    header('Location: ../../index.php');
    exit;
}

$db = Database::getInstance();

// Obtener tipos de documento
$tipos_documento = $db->query("SELECT * FROM TIPOS_DOCUMENTO ORDER BY DESCRIPCION");

// Obtener áreas
$areas = $db->query("SELECT * FROM AREAS ORDER BY NOMBRE");

// Obtener tipos de archivo
$tipos_archivo = $db->query("SELECT * FROM TIPOS_ARCHIVO ORDER BY DESCRIPCION");

$pageTitle = 'Subir Nuevo Documento';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Subir Nuevo Documento</h1>
            </div>

            <!-- Alertas -->
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
                    <form id="uploadForm" action="../../actions/upload-document.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Campos de selección de documento -->
                            <div class="col-md-6 mb-3">
                                <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                <select class="form-select" id="tipo_documento" name="tipo_documento_id" required>
                                    <option value="">Seleccione un tipo...</option>
                                    <?php while ($tipo = $tipos_documento->fetch_assoc()): ?>
                                        <option value="<?php echo $tipo['ID']; ?>">
                                            <?php echo htmlspecialchars($tipo['DESCRIPCION']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="area" class="form-label">Área</label>
                                <select class="form-select" id="area" name="area_id" required>
                                    <option value="">Seleccione un área...</option>
                                    <?php while ($area = $areas->fetch_assoc()): ?>
                                        <option value="<?php echo $area['ID']; ?>">
                                            <?php echo htmlspecialchars($area['NOMBRE']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipo_archivo" class="form-label">Tipo de Archivo</label>
                                <select class="form-select" id="tipo_archivo" name="tipo_archivo_id" required>
                                    <option value="">Seleccione un tipo...</option>
                                    <?php while ($tipo = $tipos_archivo->fetch_assoc()): ?>
                                        <option value="<?php echo $tipo['ID']; ?>">
                                            <?php echo htmlspecialchars($tipo['DESCRIPCION']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="documento" class="form-label">Archivo</label>
                                <input type="file" class="form-control" id="documento" name="documento" required accept=".pdf, .doc, .docx, .xls, .xlsx" onchange="previewFile()">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Subir Documento
                            </button>
                        </div>
                    </form>

                    <!-- Previsualización -->
                    <div id="preview" class="mt-4">
                        <h5>Previsualización:</h5>
                        <div id="previewContent"></div>
                    </div>

                    <!-- Barra de progreso -->
                    <div id="progressBar" class="progress mt-3" style="display: none;">
                        <div id="progress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function previewFile() {
    const fileInput = document.getElementById('documento');
    const previewContent = document.getElementById('previewContent');
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');

    // Limpiar contenido anterior
    previewContent.innerHTML = '';
    
    const file = fileInput.files[0];
    if (file) {
        const fileType = file.type;
        
        // Muestra barra de progreso
        progressBar.style.display = 'block';
        let percent = 0;
        const interval = setInterval(() => {
            percent += 10;
            if (percent > 100) {
                clearInterval(interval);
            }
            progress.style.width = percent + '%';
            progress.setAttribute('aria-valuenow', percent);
        }, 100); // Simula el progreso

        // Previsualización según el tipo de archivo
        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContent.innerHTML = '<img src="' + e.target.result + '" alt="Previsualización" class="img-fluid" />';
                clearInterval(interval); // Detiene la simulación
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            previewContent.innerHTML = '<iframe src="' + URL.createObjectURL(file) + '" style="width: 100%; height: 400px;" frameborder="0"></iframe>';
            clearInterval(interval); // Detiene la simulación
        } else if (fileType === 'application/msword' || fileType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            previewContent.innerHTML = '<p>Previsualización de Word no soportada directamente. Sube el documento para verlo.</p>';
            clearInterval(interval); // Detiene la simulación
        } else if (fileType === 'application/vnd.ms-excel' || fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            previewContent.innerHTML = '<p>Previsualización de Excel no soportada directamente. Sube el documento para verlo.</p>';
            clearInterval(interval); // Detiene la simulación
        } else {
            previewContent.innerHTML = '<p>Tipo de archivo no soportado para previsualización.</p>';
            clearInterval(interval); // Detiene la simulación
        }
    }
}
</script>


<?php include '../../includes/footer.php'; ?>
