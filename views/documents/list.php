<?php
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

$user = new User();
$user->loadById($_SESSION['user_id']);

if (!$user->hasPermission('ver_documentos')) {
    $_SESSION['error'] = 'No tienes permisos para ver documentos';
    //header('Location: ../../index.php');
    //exit;
}

$db = Database::getInstance();

// Filtros
$where = "1=1";
$params = [];
$types = "";

if (isset($_GET['tipo_documento']) && !empty($_GET['tipo_documento'])) {
    $where .= " AND d.TIPO_DOCUMENTO_ID = ?";
    $params[] = $_GET['tipo_documento'];
    $types .= "i";
}

if (isset($_GET['area']) && !empty($_GET['area'])) {
    $where .= " AND d.AREA_ID = ?";
    $params[] = $_GET['area'];
    $types .= "i";
}

// Consulta paginada
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Prepara la consulta
$sql = "
    SELECT 
        d.*,
        td.DESCRIPCION as TIPO_DOCUMENTO,
        a.NOMBRE as AREA,
        u.NOMBRE as USUARIO
    FROM DOCUMENTOS d
    JOIN TIPOS_DOCUMENTO td ON d.TIPO_DOCUMENTO_ID = td.ID
    JOIN AREAS a ON d.AREA_ID = a.ID
    JOIN USUARIOS u ON d.USUARIO_ID = u.ID
    WHERE $where
    ORDER BY d.FECHA_INSERCION DESC
    LIMIT $per_page OFFSET $offset
";

$stmt = $db->prepare($sql);
if (!$stmt) {
    die('Error en la consulta: ');
}

// Si hay parámetros, los vinculamos
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$documentos = $stmt->get_result();

// Obtener total de registros para paginación
$total_sql = "SELECT COUNT(*) as total FROM DOCUMENTOS d WHERE $where";
$stmt = $db->prepare($total_sql);
if (!$stmt) {
    die('Error en la consulta: ' . $db->error);
}

if (!empty($params)) {
    array_pop($params); // Remove LIMIT
    array_pop($params); // Remove OFFSET
    if (!empty($params)) {
        $stmt->bind_param(substr($types, 0, -2), ...$params);
    }
}

$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);

$pageTitle = 'Listado de Documentos';
include '../../includes/header.php';
include '../../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Documentos</h1>
                <?php if ($user->hasPermission('crear_documentos')): ?>
                    <a href="create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Documento
                    </a>
                <?php endif; ?>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                            <select class="form-select" id="tipo_documento" name="tipo_documento">
                                <option value="">Todos</option>
                                <?php
                                $tipos = $db->query("SELECT * FROM TIPOS_DOCUMENTO ORDER BY DESCRIPCION");
                                while ($tipo = $tipos->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $tipo['ID']; ?>"
                                        <?php echo isset($_GET['tipo_documento']) && $_GET['tipo_documento'] == $tipo['ID'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo['DESCRIPCION']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="area" class="form-label">Área</label>
                            <select class="form-select" id="area" name="area">
                                <option value="">Todas</option>
                                <?php
                                $areas = $db->query("SELECT * FROM AREAS ORDER BY NOMBRE");
                                while ($area = $areas->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $area['ID']; ?>"
                                        <?php echo isset($_GET['area']) && $_GET['area'] == $area['ID'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($area['NOMBRE']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de documentos -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Área</th>
                            <th>Subido por</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($doc = $documentos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $doc['ID']; ?></td>
                                <td><?php echo htmlspecialchars($doc['TIPO_DOCUMENTO']); ?></td>
                                <td><?php echo htmlspecialchars($doc['AREA']); ?></td>
                                <td><?php echo htmlspecialchars($doc['USUARIO']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($doc['FECHA_INSERCION'])); ?></td>
                                <td>
                                    <?php if ($user->hasPermission('ver_documentos')): ?>
                                        <a href="view.php?id=<?php echo $doc['ID']; ?>" class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($user->hasPermission('editar_documentos')): ?>
                                        <a href="edit.php?id=<?php echo $doc['ID']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($user->hasPermission('eliminar_documentos')): ?>
                                        <button type="button" class="btn btn-sm btn-danger" title="Eliminar"
                                                onclick="confirmDelete(<?php echo $doc['ID']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&tipo_documento=<?php echo $_GET['tipo_documento'] ?? ''; ?>&area=<?php echo $_GET['area'] ?? ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('¿Está seguro de que desea eliminar este documento?')) {
        window.location.href = '../../actions/delete-document.php?id=' + id;
    }
}
</script>

<?php include '../../includes/footer.php'; ?>
