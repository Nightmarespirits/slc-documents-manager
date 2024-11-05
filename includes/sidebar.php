<style>
    .sidebar {
        background-color: #343a40; /* Color oscuro de fondo */
        color: #fff; /* Color de texto blanco */
        height: 100vh; /* Altura completa de la pantalla */
    }

    .sidebar-logo {
        text-align: center;
        padding: 1rem;
        background-color: #212529; /* Color de fondo de la cabecera */
    }

    .sidebar-logo h2 {
        font-size: 1.5rem;
        color: #ffffff; /* Texto blanco */
        margin-top: 0.5rem;
    }

    .sidebar .nav-link {
        font-size: 1rem; /* Aumenta el tamaño de la fuente */
        color: #adb5bd; /* Color de los enlaces */
        padding: 0.75rem 1.25rem;
        transition: color 0.3s ease, background-color 0.3s ease;
    }

    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        color: #ffffff; /* Texto blanco al pasar el cursor o si es activo */
        background-color: #495057; /* Fondo de enlace activo o hover */
    }

    .sidebar .nav-link i {
        margin-right: 0.5rem;
        font-size: 1.2rem; /* Aumenta el tamaño del ícono */
    }

    .sidebar .nav-item {
        border-bottom: 1px solid #495057; /* Separador entre elementos */
    }
</style>

<div class="sidebar col-md-3 col-lg-2 d-md-block">
    <div class="sidebar-sticky">
        <div class="sidebar-logo">
            <img src="logo.png" alt="Logo" class="img-fluid" style="max-width: 60%; margin-top: 1rem;">
            <h2>GESTION DOCUMENTOS</h2>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/slc-documents-manager/views/dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/slc-documents-manager/views/documents/create.php">
                    <i class="fas fa-file-upload"></i> Subir Documento
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/slc-documents-manager/views/documents/list.php">
                    <i class="fas fa-file-alt"></i> Ver Documentos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/slc-documents-manager/views/documents/areas.php">
                    <i class="fas fa-th-list"></i> Documentos por Áreas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/slc-documents-manager/views/settings.php">
                    <i class="fas fa-cog"></i> Configuración
                </a>
            </li>
        </ul>
    </div>
</div>
