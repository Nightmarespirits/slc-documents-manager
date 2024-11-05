<?php
// views/profile.php
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

$pageTitle = 'Editar Perfil';
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Mi Perfil</h1>
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

            <div class="row">
                <!-- Información del perfil -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <form action="../actions/update-profile.php" method="POST">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($user->getNombre()); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           value="<?php echo htmlspecialchars($user->getTelefono()); ?>">
                                </div>

                                <button type="submit" name="action" value="update_info" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cambio de contraseña -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Cambiar Contraseña</h5>
                        </div>
                        <div class="card-body">
                            <form action="../actions/update-profile.php" method="POST" onsubmit="return validatePasswordForm()">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password" required>
                                    <div class="form-text">
                                        La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, 
                                        minúsculas y números.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required>
                                </div>

                                <button type="submit" name="action" value="change_password" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de actividad -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $actividad = $user->getActivityLog();
                                while ($log = $actividad->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($log['FECHA'])); ?></td>
                                    <td><?php echo htmlspecialchars($log['ACCION']); ?></td>
                                    <td><?php echo htmlspecialchars($log['DETALLES']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function validatePasswordForm() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Verificar que las contraseñas coincidan
    if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return false;
    }

    // Verificar requisitos de contraseña
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
    if (!passwordRegex.test(newPassword)) {
        alert('La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números');
        return false;
    }

    return true;
}

// Mostrar/ocultar contraseña
document.querySelectorAll('input[type="password"]').forEach(input => {
    const toggleButton = document.createElement('button');
    toggleButton.type = 'button';
    toggleButton.className = 'btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y';
    toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
    toggleButton.style.right = '10px';
    toggleButton.onclick = function() {
        input.type = input.type === 'password' ? 'text' : 'password';
        toggleButton.innerHTML = input.type === 'password' ? 
            '<i class="fas fa-eye"></i>' : 
            '<i class="fas fa-eye-slash"></i>';
    };

    input.parentElement.style.position = 'relative';
    input.parentElement.appendChild(toggleButton);
});
</script>

<?php include '../includes/footer.php'; ?>